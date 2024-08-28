<?php

namespace Modules\Elastic\Services;

use App\Helpers\Helper;
use Carbon\Carbon;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Auth;
use Modules\Elastic\Entities\SellerElastic;
use Modules\Product\Repositories\Interface\ProductRepositoryInterface;
use Modules\Product\Services\ProductService;
use Modules\Seller\Repositories\Interface\SellerRepositoryInterface;
use Modules\Seller\Services\SellerProductService;
use Modules\Seller\Services\SellerService;
use stdClass;

class SellerElasticService extends BaseElasticService
{
    protected $fieldSuggest = 'product_name';
    protected $keySuggest = 'product_name.suggest';
    // protected $pipelineId = 'add_seller_name_initial';
    protected SellerRepositoryInterface $sellerProductRepositoryInterface; ///
    public function __construct(SellerRepositoryInterface $sellerProductRepositoryInterface)
    {
        parent::__construct();
        // $this->sellerProductService = $sellerProductService;
        $this->sellerProductRepositoryInterface = $sellerProductRepositoryInterface;
    }

    protected function getModel(): string
    {
        return \Modules\Seller\Entities\Seller::class;
    }
    public function getSyncDataFromDB($page = null)
    {
        // return $this->sellerProductRepositoryInterface->all();

        return $this->sellerProductRepositoryInterface->getAllWithRelationship(1000);
    }
    protected function getSettings()
    {
        return [
            'analysis' => [
                'tokenizer' => [
                    'edge_ngram_tokenizer' => [
                        'type' => 'edge_ngram',
                        'min_gram' => 3,
                        'max_gram' => 20,
                        'token_chars' => ['letter', 'digit']
                    ]
                ],
                'analyzer' => [
                    'product_search' => [
                        'type' => 'custom',
                        'tokenizer' => 'edge_ngram_tokenizer',
                        'filter' => ['lowercase']
                    ],
                    "trigram" => [
                        "type" => "custom",
                        "tokenizer" => "standard",
                        "filter" => ["lowercase", "shingle"]
                    ],
                    "reverse" => [
                        "type" => "custom",
                        "tokenizer" => "standard",
                        "filter" => ["lowercase", "reverse"]
                    ],
                ],
                "filter" => [
                    "shingle" => [
                        "type" => "shingle",
                        "min_shingle_size" => 2,
                        "max_shingle_size" => 3,
                        "preserve_separators" => true
                    ]
                ]
            ]
        ];
    }

    public function getProperties()
    {
        return SellerElastic::getProperties();
    }
    protected function getPipelineBody(): array|null
    {
        return [
            'description' => 'Add seller_name_initial field',
            'processors' => [
                [
                    'set' => [
                        'field' => 'seller_name_initial',
                        'value' => [
                            'script' => [
                                'source' => "return ctx.seller_name.keyword.substring(0, 1).toUpperCase();"
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
    protected function buildScriptCalculatePrice($memberType, $returnString)
    {
        return [
            'source' => "
                double discount = doc['products.skus.{$memberType}_discount'].size() > 0 ? doc['products.skus.{$memberType}_discount'].value : 0;
                long now = new Date().getTime();
                double originalPrice = doc['products.skus.{$memberType}_price'].size() > 0 ? doc['products.skus.{$memberType}_price'].value : 0;
                double flashSaleDiscount = 0;
                if(doc['products.product_flash_sale_active.start_time'].size() > 0 && doc['products.product_flash_sale_active.end_time'].size() > 0)
                {
                    long startTime = doc['products.product_flash_sale_active.start_time'].value.toInstant().toEpochMilli();
                    long endTime = doc['products.product_flash_sale_active.end_time'].value.toInstant().toEpochMilli();
                    if (startTime <= now && endTime > now) {
                        discount = Math.min(discount + doc['products.product_flash_sale_active.discount'].value, 1.0);
                    }
                }
                double calculatedValue = originalPrice * (1 - discount);
                return {$returnString};
            ",
            'lang' => 'painless'
        ];
    }
    public function search(string $txtSearch = null, int $page = 1, array $categoryIds = null, int $brandId = null, string $sort = 'score', bool $sale = false, bool $new = false, int  $minPrice = 0, int  $maxPrice = null, array $sellerIds = null, array $specificationValues = null, bool $loadFilerSellerAndSpecification = false)
    {
        $perPage = ProductService::PER_PAGE;
        $memberType = Helper::getMemberType();
        $from = $perPage * ($page - 1);
        $must = [];
        if (isset($txtSearch)) {
            $must[] =
                ['bool' => [
                    'should' => [
                        // ['fuzzy' => ['products.product_name' => [
                        //     'value' => $txtSearch,
                        //     'fuzziness' => 'AUTO'
                        // ]]],
                        // ['match' => ['product_name' => $txtSearch]],
                        [
                            'match' => ['products.product_name' => [
                                'query' => $txtSearch,
                                'boost' => 5
                            ]],
                        ],
                        // [
                        //     'match' => ['products.describe' => [
                        //         'query' => $txtSearch,
                        //         'boost' => 1
                        //     ]],
                        // ],
                        // ['match' => ['products.detail' => [
                        //     'query' => $txtSearch,
                        //     'boost' => 1
                        // ]]],
                    ]
                ]];
        }
        if (isset($brandId)) {
            $must[] = ['match' => ['products.brand_id' => $brandId]];
        }
        if (isset($categoryIds)) {
            $must[] = ['terms' => ['products.category_id' => $categoryIds]];
        }

        if ($sale == true) {
            $must[] = [
                'bool' => [
                    'should' => [
                        [
                            'nested' => [
                                'path' => 'products.skus',
                                'query' => [
                                    'bool' => [
                                        'must' => [
                                            ['match' => ['products.skus.default' => true]],
                                            ['range' => ["products.skus.{$memberType}_discount" => [
                                                'gt' => 0,
                                            ]]]
                                        ]
                                    ]
                                ]
                            ],


                        ],
                        [
                            'bool' => [
                                'must' => [
                                    [
                                        'exists' => [
                                            'field' => 'products.product_flash_sale_active',
                                        ]
                                    ],
                                    [
                                        'range' => ["products.product_flash_sale_active.start_time" => [
                                            'lte' => 'now',
                                        ]]
                                    ],
                                    [
                                        'range' => ["products.product_flash_sale_active.end_time" => [
                                            'gt' => 'now',
                                        ]]
                                    ],
                                    [
                                        'range' => ["products.product_flash_sale_active.discount" => [
                                            'gt' => 0,
                                        ]]
                                    ]
                                ]
                            ],




                        ]
                    ]
                ]
            ];
        }
        if ($new == true) {
            $daysThreshold = ProductService::DAYS_THRESHOLD;
            $must[] = [
                'range' => ['products.created_at' => [
                    'gte' => "now-{$daysThreshold}d/d" //Carbon::now()->subDays($daysThreshold)
                ]]
            ];
        }
        $skuDefault = [
            'nested' => [
                'path' => 'products.skus',
                'query' => [
                    'bool' => [
                        'filter' => [
                            ['match' => ['products.skus.default' => true]],
                        ],
                    ]
                ],
            ],
        ];
        if (isset($maxPrice) && $maxPrice > $minPrice) {
            $skuDefault['nested']['query']['bool']['must'] = [
                'script' => [
                    'script' => $this->buildScriptCalculatePrice(
                        $memberType,
                        "(calculatedValue >= {$minPrice} && calculatedValue <= {$maxPrice})"
                    )
                ]
            ];
        }
        $must[] = $skuDefault;

        if (isset($specificationValues) && count($specificationValues) > 0) {
            $must[] = [
                'nested' => [
                    'path' => 'products.specifications',
                    'query' => [
                        'bool' => [
                            'must' => [
                                ['terms' => ['products.specifications.specification_value' => $specificationValues]]
                            ]
                        ]
                    ]
                ]

            ];
        }
        $filter = null;
        if (count($must) > 0) {
            $filter = [
                "bool" => [
                    "must" => $must
                ]
            ];
        } else {
            $filter = [
                "bool" => new stdClass()
            ];
        }
        $filterSeller = null;
        if (isset($sellerIds) && count($sellerIds) > 0) {
            $filterSeller = [
                'bool' => [
                    'must' => [
                        ['terms' => ['id' => $sellerIds]]
                    ]
                ]

            ];
        } else {
            $filterSeller = [
                "bool" => new stdClass()
            ];
        }
        $aggs = [
            'filter_seller' => [
                'filter' => $filterSeller,
                'aggs' => [
                    'nested_product' => [
                        'nested' => [
                            'path' => 'products'
                        ],
                        'aggs' => [
                            "filtered" => [
                                "filter" => $filter,
                                "aggs" => [
                                    'product_list' => [
                                        'terms' => [
                                            'field' => 'products.id',
                                            'size'  => 50000
                                        ],

                                        'aggs' => [
                                            'product_detail' => [
                                                'top_hits' => [
                                                    '_source' => [
                                                        'includes' => [
                                                            'products.id',
                                                            'products.product_name',
                                                            'products.cover_image',
                                                            'products.shipping_point',
                                                            'products.category_id',
                                                            'products.brand_id',
                                                            'products.created_at',
                                                            'products.average_rating',
                                                            'products.total_rating',
                                                            'products.total_quantity_sold',
                                                            'products.product_flash_sale_active.start_time',
                                                            'products.product_flash_sale_active.end_time',
                                                            'products.product_flash_sale_active.discount'
                                                        ]
                                                    ],
                                                    'size' => 1
                                                ]
                                            ],
                                            'sku_product_nested' => [
                                                'nested' => [
                                                    'path' => 'products.skus'
                                                ],
                                                'aggs' => [
                                                    'sku_default' => [
                                                        'filter' => [
                                                            'term' => [
                                                                'products.skus.default' => true
                                                            ]
                                                        ],
                                                        'aggs' => [
                                                            'sku_detail' => [
                                                                'top_hits' => [
                                                                    '_source' => [
                                                                        'includes' => [
                                                                            'products.skus.id',
                                                                            'products.skus.guest_price',
                                                                            'products.skus.guest_discount',
                                                                            'products.skus.member_retail_price',
                                                                            'products.skus.member_retail_discount',
                                                                            'products.skus.member_wholesale_price',
                                                                            'products.skus.member_wholesale_discount',
                                                                            'products.skus.default',
                                                                            'products.skus.product_part_number'
                                                                        ]
                                                                    ],
                                                                    'size' => 1
                                                                ]
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ],
                                            'filtered_flash_sale' => [
                                                'filter' => [
                                                    'bool' => [
                                                        'must' => [
                                                            ['exists' => ['field' => 'products.product_flash_sale_active']],
                                                            ['range' => ['products.product_flash_sale_active.start_time' => ['lte' => 'now']]],
                                                            ['range' => ['products.product_flash_sale_active.end_time' => ['gt' => 'now']]],
                                                            ['range' => ['products.product_flash_sale_active.discount' => ['gt' => 0]]]
                                                        ]
                                                    ]
                                                ],
                                                'aggs' => [
                                                    'field_flash_sale_discount' => [
                                                        'max' => [
                                                            'field' => 'products.product_flash_sale_active.discount',
                                                        ]
                                                    ]
                                                ]
                                            ],
                                            'discounted' => [
                                                'bucket_script' => [
                                                    'buckets_path' => [
                                                        'discount' => "sku_product_nested>sku_default>sku_detail[_source.{$memberType}_discount]",
                                                        'flash_sale_discount' => 'filtered_flash_sale>field_flash_sale_discount'
                                                    ],
                                                    'gap_policy' => 'insert_zeros',
                                                    'script' => [
                                                        'source' => "   
                                            return Math.min(params.discount + params.flash_sale_discount, 1.0);
                                        "
                                                    ]
                                                ]
                                            ],
                                            'discounted_price' => [
                                                'bucket_script' => [
                                                    'buckets_path' => [
                                                        'price' => "sku_product_nested>sku_default>sku_detail[_source.{$memberType}_price]",
                                                        'discounted' => 'discounted',
                                                    ],
                                                    'gap_policy' => 'insert_zeros',
                                                    'script' => [
                                                        'source' => "
                                            return params.price * (1 - params.discounted);
                                        "
                                                    ]
                                                ]
                                            ],
                                            // 'sorted_by_discount' => 
                                        ]
                                    ]
                                ]

                            ]
                        ]
                    ]
                ]
            ]
        ];
        if ($loadFilerSellerAndSpecification == true) {
            $aggs['filter_seller']['aggs']['nested_product']['aggs']['filtered']['aggs']['specifications'] = [
                'nested' => [
                    'path' => 'products.specifications'
                ],
                'aggs' => [
                    "specification_name_group" => [
                        "terms" => [
                            "field" => "products.specifications.specification_name.keyword",
                            // "size" => 10 // Số lượng bucket tối đa trả về
                        ],
                        'aggs' => [
                            'specification_values' => [
                                "terms" => [
                                    "field" => "products.specifications.specification_value.keyword",
                                    // "size" => 10 // Số lượng bucket tối đa trả về
                                ],
                                'aggs' => [
                                    "specificationDetail" => [
                                        "top_hits" => [
                                            "_source" => [
                                                "include" => [
                                                    'products.specifications.id',
                                                    'products.specifications.specification_value',
                                                ]
                                            ],
                                            'size' => 1,
                                        ],
                                    ],
                                ],
                            ]
                        ]
                    ]

                ]
            ];
            $aggs['filter_seller']['aggs']['nested_product']['aggs']['filtered']['aggs']['sellers'] = [
                'reverse_nested' => new stdClass(),
                'aggs' => [
                    'seller_name_initial_group' => [

                        "terms" => [
                            "field" => "seller_name_initial",
                            "order" => [
                                "_key" => "asc"  // Sắp xếp theo giá trị của trường 'sellers.seller_name_initial'
                            ]
                        ],
                        'aggs' => [
                            "sellers" => [
                                "terms" => [
                                    "field" => "seller_name.keyword",
                                    "order" => [
                                        "_key" => "asc"
                                    ]
                                ],
                                'aggs' => [
                                    "sellerDetail" => [
                                        "top_hits" => [
                                            "_source" => [
                                                "include" => [
                                                    'id',
                                                    'logo',
                                                ]
                                            ],
                                            'size' => 1,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    // "seller_name_group" => [
                    //     "composite" => [
                    //         "sources" => [
                    //             ["seller_id" => ["terms" => ["field" => "sellers.id"]]],
                    //             ["seller_name" =>  ["terms" => ["field" => "sellers.seller_name.keyword"]]],
                    //             ["logo" => ["terms" => ["field" => "sellers.logo"]]]
                    //         ]
                    //     ],
                    //     'aggs' => [
                    //         'product_reverse_nested' => [
                    //             'reverse_nested' => new \stdClass(),
                    //         ]
                    //     ]
                    // ],


                ]
            ];
        }
        if ($sort != 'score') {
            $filedSort = null;
            $order = null;
            switch ($sort) {
                case 'hot':
                    $filedSort = 'product_detail[_source.total_quantity_sold]';
                    $order  = 'DESC';
                    break;
                case 'rating':
                    $filedSort = 'product_detail[_source.average_rating]';
                    $order = 'DESC';
                    break;
                    // case 'az':
                    //     $filedSort = 'products.product_name.keyword';
                    //     $scriptSort['order'] = 'ASC';
                    //     break;
                    // case 'za':
                    //     $filedSort = 'products.product_name.keyword';
                    //     $scriptSort['order'] = 'DESC';
                    //     break;
                case 'p-asc':
                    $filedSort = 'discounted_price';
                    $order  = 'ASC';
                    break;
                case 'p-desc':
                    $filedSort = 'discounted_price';
                    $order = 'DESC';
                    break;
                default:
                    # code...
                    break;
            }
            $aggs['filter_seller']['aggs']['nested_product']['aggs']['filtered']['aggs']['product_list']['aggs']['sorted'] = [
                'bucket_sort' => [
                    'sort' => [
                        [
                            $filedSort => [
                                'order' => $order
                            ]
                        ]
                    ],
                    'from' => $from,
                    'size' => $perPage,
                ]
            ];
        }
        $body = [
            // 'runtime_mappings' => [
            //     'seller_name_initial' => [
            //         'type' => 'keyword',
            //         'script' => [
            //             'source' => "return doc['seller_name.keyword'].value.substring(0, 1).toUpperCase();"
            //         ]
            //     ]
            // ],
            'aggs' => $aggs
        ];
        // $body['aggs'] = $aggs;


        $params = [
            'index' => $this->index,
            'body' => $body,
            'size' => 0,
            'track_total_hits' => true,
            'score_mode' => 'avg'
        ];
        // return $params;

        $response = $this->elasticClient()->search($params);
        if ($response['hits']['total']['value'] == 0) {
            return ['products' => [], 'totalPage' => 0];
        }
        $filtered = $response['aggregations']['filter_seller']['nested_product']['filtered'];
        $products = $filtered['product_list']['buckets'];
        $totalPage = ceil($filtered['doc_count'] / $perPage);
        $data = [
            'currentPage' => $page,
            'products' => $products,
            'aggregations' => $response['aggregations'],
            'totalPage' => $totalPage,
            'lastPage' => $totalPage,
            'total' => $filtered['doc_count'],
        ];
        if ($loadFilerSellerAndSpecification == true) {
            $data['filter'] = [
                'sellers' => $filtered['sellers'],
                'specifications' => $filtered['specifications']
            ];
        }
        return $data;
    }
}
