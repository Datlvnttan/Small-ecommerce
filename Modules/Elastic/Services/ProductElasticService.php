<?php

namespace Modules\Elastic\Services;

use App\Helpers\Helper;
use Carbon\Carbon;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Auth;
use Modules\Elastic\Entities\ProductFlashSaleElastic;
use Modules\Elastic\Entities\SellerElastic;
use Modules\Elastic\Entities\SkuElastic;
use Modules\Elastic\Entities\SpecificationElastic;
use Modules\Product\Repositories\Interface\ProductRepositoryInterface;
use Modules\Product\Services\ProductService;

class ProductElasticService extends BaseElasticService
{
    protected $fieldSuggest = 'product_name';
    protected $sourcesSuggest = 'product_name';
    protected $pipelineId = 'generate_suggest_product_name';
    protected $productRepositoryInterface;

    public function __construct(ProductRepositoryInterface $productRepositoryInterface)
    {
        parent::__construct();
        $this->productRepositoryInterface = $productRepositoryInterface;
    }
    protected function getModel(): string
    {
        return \Modules\Product\Entities\Product::class;
    }
    protected function getSettings()
    {
        return [
            "max_result_window" => 1000000,
            // "index.max_shingle_diff" => 20,
            'analysis' => [
                'analyzer' => [
                    // 'suggest_analyzer' => [
                    //     'type' => 'custom',
                    //     'tokenizer' => 'whitespace',
                    //     'filter' => ['lowercase', 'shingle_custom', 'preserve_only_first', 'after_last_space']
                    // ],
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
                    // "first_character_analyzer" => [
                    //     "type" => "custom",
                    //     "tokenizer" => "keyword",
                    //     "filter" => ["lowercase", "initial_character_filter"]
                    // ],
                ],
                "filter" => [
                    // "initial_character_filter" => [
                    //     "type" => "pattern_replace",
                    //     "pattern" => ".*",
                    //     "replacement" => "$1"
                    // ],
                    // 'after_last_space' => [
                    //     'type' => 'pattern_replace',
                    //     'pattern' => '(.* )',
                    //     'replacement' => ''
                    // ],
                    // "shingle_custom" => [
                    //     "type" => "shingle",
                    //     "min_shingle_size" => 2,
                    //     "max_shingle_size" => 3,
                    //     "preserve_separators" => true,
                    //     "output_unigrams" => true,
                    //     'position_increment' => 0
                    // ],
                    // 'preserve_only_first' => [
                    //     'type' => 'predicate_token_filter',
                    //     'script' => [
                    //         'source' => "token.position == 0"
                    //     ]
                    // ],
                ],
                "normalizer" => [
                    "lowercase" => [
                        "type" => "custom",
                        "filter" => ["lowercase"]
                    ]
                ],

            ]
        ];
    }
    public function getProperties()
    {
        return [
            'id' => ['type' => 'keyword'],
            'cover_image' => ['type' => 'keyword'],
            // 'product_flash_sale_discount' => ['type' => 'double'],
            // 'product_flash_sale_start_time' => [
            //     'type' => 'date',
            //     'format' => 'strict_date_time'
            // ],
            // 'product_flash_sale_end_time' => [
            //     'type' => 'date',
            //     'format' => 'strict_date_time'
            // ],
            'product_name' => [
                'type' => 'text',
                'store' => true,
                'position_increment_gap' => 0,
                "fields" => [
                    "keyword" => [
                        "type" => "keyword"
                    ],
                    'trigram' => [
                        'type' => "text",
                        'analyzer' => 'trigram'
                    ],
                    "reverse" => [
                        "type" => "text",
                        "analyzer" => "reverse"
                    ],
                    'suggest' => [
                        "type" => "completion",
                        'preserve_separators' => false,
                        // 'analyzer' => 'suggest_analyzer',
                        "search_analyzer" => "standard",
                        "preserve_position_increments" => false
                    ],
                ],
            ],
            'suggest' => [
                "type" => "completion",
                'preserve_separators' => false,
            ],
            'shipping_point' => ['type' => 'integer'],
            'describe' => ['type' => 'text'],
            'detail' => ['type' => 'text'],
            'category_id' => ['type' => 'keyword'],
            'brand_id' => ['type' => 'keyword'],
            'average_rating' => ['type' => 'double'],
            'total_rating' => ['type' => 'integer'],
            'total_quantity_sold' => ['type' => 'integer'],
            'created_at' => ['type' => 'date', 'format' => 'strict_date_time'],
            'updated_at' => ['type' => 'date', 'format' => 'strict_date_time'],
            'product_flash_sale_active' => [
                'type' => 'object',
                'dynamic' => false,
                // 'dynamic' => true,
                'properties' => ProductFlashSaleElastic::getProperties(),
                // 'ignore_malformed' => true,
            ],
            'sellers' => [
                'type' => 'nested',
                'dynamic' => false,
                'properties' => SellerElastic::getProperties(),
            ],
            'specifications' => [
                'type' => 'nested',
                'dynamic' => false,
                'properties' => SpecificationElastic::getProperties(),
            ],
            'skus' => [
                'type' => 'nested',
                'dynamic' => false,
                'properties' => SkuElastic::getProperties(),
            ],
        ];
    }
    protected function getPipelineBody(): array|null
    {
        return [
            'description' => '',
            'processors' => [
                // [
                //     'set' => [
                //         'field' => 'suggest',
                //         'value' => [
                //             'script' => [
                //                 'source' => "ctx.suggest = []; String text = ctx.name.trim(); ctx.suggest.add(text);for (int i = 0; i < text.length(); i++) {if(String.valueOf(text.charAt(i)).equals(' ')){ ctx.suggest.add(text.substring(i+1));}}"
                //             ]
                //         ]
                //     ]
                // ]
                [
                    'script' => [
                        'source' => 
                            "ctx.suggest = []; 
                            String text = ctx.product_name.trim();
                            ctx.suggest.add(text);
                            for (int i = 0; i < text.length(); i++) 
                            {
                                if(String.valueOf(text.charAt(i)).equals(' '))
                                { 
                                    ctx.suggest.add(text.substring(i+1));
                                }
                            }"
                    ]
                ]
            ]
        ];
    }
    // public function getProperties()
    // {
    //     return [
    //         'id' => ['type' => 'keyword'],
    //         'sku_id' => ['type' => 'keyword'],
    //         'product_part_number' => ['type' => 'keyword'],
    //         'sku_quantity' => ['type' => 'integer'],
    //         'guest_price' => ['type' => 'integer'],
    //         'guest_discount' => ['type' => 'double'],
    //         'cover_image' => ['type' => 'keyword'],
    //         'member_retail_price' => ['type' => 'integer'],
    //         'member_retail_discount' => ['type' => 'double'],
    //         'member_wholesale_price' => ['type' => 'integer'],
    //         'member_wholesale_discount' => ['type' => 'double'],
    //         'product_flash_sale_discount' => ['type' => 'double'],
    //         'product_flash_sale_start_time' => [
    //             'type' => 'date',
    //             'format' => 'strict_date_time'
    //         ],
    //         'product_flash_sale_end_time' => [
    //             'type' => 'date',
    //             'format' => 'strict_date_time'
    //         ],
    //         'product_name' => [
    //             'type' => 'text',
    //             "fields" => [
    //                 "keyword" => [
    //                     "type" => "keyword"
    //                 ],
    //                 'trigram' => [
    //                     'type' => "text",
    //                     'analyzer' => 'trigram'
    //                 ],
    //                 "reverse" => [
    //                     "type" => "text",
    //                     "analyzer" => "reverse"
    //                 ],
    //                 'suggest' => [
    //                     "type" => "completion",
    //                     'preserve_separators' => false
    //                 ]
    //             ],
    //             // 'analyzer' => 'product_search'
    //         ],
    //         'shipping_point' => ['type' => 'integer'],
    //         'describe' => ['type' => 'text'],
    //         'detail' => ['type' => 'text'],
    //         'category_id' => ['type' => 'keyword'],
    //         'brand_id' => ['type' => 'keyword'],
    //         'average_rating' => ['type' => 'double'],
    //         'total_rating' => ['type' => 'integer'],
    //         'total_quantity_sold' => ['type' => 'integer'],
    //         'created_at' => ['type' => 'date'],
    //         'updated_at' => ['type' => 'date'],
    //         // $this->keySuggest => [
    //         //     'type' => 'completion',
    //         // ]

    //     ];
    // }
    // protected function getRuntimeField()
    // {
    //     return [
    //         'price_new'=>[
    //             'type' => 'double',
    //         ]
    //         ];
    // }

    public function getSyncDataFromDB($page = null)
    {
        $data = $this->productRepositoryInterface->getAllWithRelationship($page, 200);
        return $data;
    }
    protected function buildScriptCalculatePrice($memberType, $returnString)
    {
        return [
            'source' => "
                double discount = doc['{$memberType}_discount'].size() > 0 ? doc['{$memberType}_discount'].value : 0;
                double flashSaleDiscount = (doc['product_flash_sale_end_time'].size() > 0 && doc['product_flash_sale_discount'].size() > 0) ? doc['product_flash_sale_discount'].value : 0;
                double finalDiscount = Math.min(discount + flashSaleDiscount, 1.0);
                double originalPrice = doc['{$memberType}_price'].size() > 0 ? doc['{$memberType}_price'].value : 0;
                double calculatedValue = originalPrice * (1 - finalDiscount);
                {$returnString};
            ",
            'lang' => 'painless'
        ];
    }
    public function sanitizeSearchQueryString(string $txtSearch, array $categoryIds = null, int $brandId = null)
    {
        $collateSource = [
            'bool' => [
                'must' => [
                    [
                        'match' => [
                            'product_name' => '{{suggestion}}'
                        ]
                    ]
                ]
            ]

        ];
        if (isset($categoryIds)) {
            $collateSource['bool']['must'][] = [
                'terms' => [
                    'category_id' => $categoryIds
                ]
            ];
        }
        if (isset($brandId)) {
            $collateSource['bool']['must'][] = [
                'match' => [
                    'brand_id' => $brandId
                ]
            ];
        }
        $prams = [
            'index' => $this->index,
            'body' => [
                'suggest' => [
                    "phrase-suggestion" => [
                        'text' => $txtSearch,
                        "phrase" => [
                            'field' => 'product_name',
                            "size" => 1,
                            "real_word_error_likelihood" => 0.7,
                            "confidence" => 1.0,
                            'max_errors' => 0.3,
                            'direct_generator' => [
                                [
                                    'field' => 'product_name.trigram',
                                    'suggest_mode' => 'always',
                                    'max_edits' => 2, // số lượng chỉnh sửa tối đa để được xem xét là 1 gợi ý, chỉ có thể là 1->2 
                                    'min_doc_freq' => 2, //gợi ý phải xuất hiện ít nhất 2 lần trong data mới đc xem xét
                                    // 'prefix_length'=>2 // Số lượng tiền tố tối thiểu cần phải khớp mới đc xem là 1 gợi ý (default:1)
                                    'min_word_length' => 2, // những từ có ít nhất 2 kí tự mới được gợi ý(df:4)
                                ]
                            ],
                            'collate' => [
                                'query' => [
                                    'source' => $collateSource
                                ],
                                'prune' => true,
                            ],

                            // "highlight" => [
                            //     "pre_tag" => "<em>",
                            //     "post_tag" => "</em>"
                            // ]
                        ],
                    ]
                ]
            ]
        ];
        $data = $this->elasticConnection->getElasticClient()->search($prams)['suggest']['phrase-suggestion'][0]['options'];
        if (count($data) > 0) {
            foreach ($data as $item) {
                if ($item['collate_match'] == true) {
                    return $item['text'];
                }
            }
        }
        return $txtSearch;
    }
    public function searchOld(string $txtSearch = null, int $page = 1, array $categoryIds = null, int $brandId = null, string $sort = 'score', bool $sale = false, bool $new = false, int  $minPrice = 0, int  $maxPrice = null)
    {
        $perPage = ProductService::PER_PAGE;
        $memberType = Helper::getMemberType();
        // $scriptCalculatePrice = ;
        // return $memberType ;
        $from = $perPage * ($page - 1);
        $must = [];
        if (isset($txtSearch)) {
            $must = [
                ['bool' => [
                    'should' => [
                        ['fuzzy' => ['product_name' => [
                            'value' => $txtSearch,
                            'fuzziness' => 'AUTO'
                        ]]],
                        // ['match' => ['product_name' => $txtSearch]],
                        [
                            'match' => ['product_name' => [
                                'query' => $txtSearch,
                                'boost' => 5
                            ]],
                        ],
                        [
                            'match' => ['describe' => [
                                'query' => $txtSearch,
                                'boost' => 1
                            ]],
                        ],
                        ['match' => ['detail' => [
                            'query' => $txtSearch,
                            'boost' => 1
                        ]]],
                    ]
                ]]
            ];
        }
        $filter = [];
        if (isset($brandId)) {
            $filter[] = ['match' => ['brand_id' => $brandId]];
        }
        if (isset($categoryIds)) {
            $filter[] = ['terms' => ['category_id' => $categoryIds]];
        }
        if ($sale == true) {
            $filter[] = [
                'bool' => [
                    'should' => [
                        [
                            'range' => ["{$memberType}_discount" => [
                                'gt' => 0,
                            ]]
                        ],
                        [
                            'bool' => [
                                'must' => [
                                    ['exists' => [
                                        'field' => 'product_flash_sale_end_time',
                                    ]],
                                    ['range' => ["product_flash_sale_discount" => [
                                        'gt' => 0,
                                    ]]]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }
        if ($new == true) {
            $filter[] = [
                'range' => ['created_at' => [
                    'gte' => Carbon::now()->subDays(ProductService::DAYS_THRESHOLD)
                ]]
            ];
        }
        if (isset($maxPrice) && $maxPrice > $minPrice) {
            $filter[] = [
                'script' => [
                    'script' => $this->buildScriptCalculatePrice($memberType, "return (calculatedValue >= {$minPrice} && calculatedValue <= {$maxPrice})")
                ]
            ];
        }
        if (isset($filter) && count($filter) > 0) {
            array_push($must, ['bool' => ['must' => $filter]]);
        }
        // $body = [
        //     'script_fields' => [
        //         'discount_new' => [
        //             'script' => [
        //                 'source' => "double discount = doc['{$memberType}_discount'].value;
        //                         double flashSaleDiscount = (doc['product_flash_sale_end_time'].size() > 0 && doc['product_flash_sale_discount'].size() > 0) ? doc['product_flash_sale_discount'].value : 0;
        //                         return (discount + flashSaleDiscount > 1.0) ? 1.0 : (discount + flashSaleDiscount);"
        //             ]
        //         ],
        //         "price_new" => [
        //             'script' => [
        //                 'source' => "double discount = doc['{$memberType}_discount'].value;
        //                         double flashSaleDiscount = (doc['product_flash_sale_end_time'].size() > 0 && doc['product_flash_sale_discount'].size() > 0) ? doc['product_flash_sale_discount'].value : 0;
        //                         double finalDiscount = (discount + flashSaleDiscount > 1.0) ? 1.0 : (discount + flashSaleDiscount);
        //                         double originalPrice = doc['{$memberType}_price'].size() > 0 ? doc['{$memberType}_price'].value : 0;
        //                         return originalPrice * (1 - finalDiscount);"
        //             ]
        //         ],
        //     ],

        // ];

        $body['query'] = [
            'bool' => [
                'must' => $must
            ]
        ];
        if ($sort != 'score') {
            // return $sort;
            $scriptSort = [];
            switch ($sort) {
                case 'hot':
                    $filedSort = 'total_quantity_sold';
                    $scriptSort['order'] = 'DESC';
                    break;
                case 'rating':
                    $filedSort = 'average_rating';
                    $scriptSort['order'] = 'DESC';
                    break;
                case 'az':
                    $filedSort = 'product_name.keyword';
                    $scriptSort['order'] = 'ASC';
                    break;
                case 'za':
                    $filedSort = 'product_name.keyword';
                    $scriptSort['order'] = 'DESC';
                    break;
                case 'p-asc':
                    $filedSort = '_script';
                    $scriptSort['order'] = 'ASC';
                    $scriptSort['type'] = 'number';
                    $scriptSort['script'] = $this->buildScriptCalculatePrice($memberType, 'return calculatedValue');
                    break;
                case 'p-desc':
                    $filedSort = '_script';
                    $scriptSort['order'] = 'DESC';
                    $scriptSort['type'] = 'number';
                    $scriptSort['script'] = $this->buildScriptCalculatePrice($memberType, 'return calculatedValue');
                    break;
                default:
                    # code...
                    break;
            }
            $body['sort'] = [
                $filedSort => $scriptSort
            ];
        }


        $params = [
            'index' => $this->index,
            '_source' => true,
            'body' => $body,
            // "scroll" => "1m",
            'from' => $from,
            'size' => $perPage,
            'track_total_hits' => true,
        ];
        $response = $this->elasticClient()->search($params)['hits'];
        // return $response;
        if ($response['total']['value'] == 0) {
            return ['products' => [], 'totalPage' => 0];
        }
        $totalPage = ceil($response['total']['value'] / $perPage);
        return [
            'currentPage' => $page,
            'products' => $response['hits'],
            'totalPage' => $totalPage,
            'lastPage' => $totalPage,
            'total' => $response['total']['value'],
        ];
    }
    // public function search(string $txtSearch = null, int $page = 1, array $categoryIds = null, int $brandId = null, string $sort = 'score', bool $sale = false, bool $new = false, int  $minPrice = 0, int  $maxPrice = null, array $sellerIds = null, array $specificationValues = null, bool $searchBySku = false, bool $loadFilerSellerAndSpecification = false)
    // {
    //     $perPage = ProductService::PER_PAGE;
    //     $memberType = Helper::getMemberType();
    //     $from = $perPage * ($page - 1);
    //     $must = [];
    //     if (isset($sellerIds) && count($sellerIds) > 0) {
    //         $must[] = [
    //             'nested' => [
    //                 'path' => 'sellers',
    //                 'query' => [
    //                     'terms' => ['sellers.id' => $sellerIds]
    //                 ]
    //             ]

    //         ];
    //     }
    //     if (isset($txtSearch)) {
    //         $must[] =
    //             ['bool' => [
    //                 'should' => [
    //                     // ['fuzzy' => ['product_name' => [
    //                     //     'value' => $txtSearch,
    //                     //     'fuzziness' => 'AUTO'
    //                     // ]]],
    //                     // ['match' => ['product_name' => $txtSearch]],
    //                     [
    //                         'match' => ['product_name' => [
    //                             'query' => $txtSearch,
    //                             'boost' => 5
    //                         ]],
    //                     ],
    //                     // [
    //                     //     'match' => ['describe' => [
    //                     //         'query' => $txtSearch,
    //                     //         'boost' => 1
    //                     //     ]],
    //                     // ],
    //                     // ['match' => ['detail' => [
    //                     //     'query' => $txtSearch,
    //                     //     'boost' => 1
    //                     // ]]],
    //                 ]
    //             ]];
    //     }
    //     if (isset($brandId)) {
    //         $must[] = ['match' => ['brand_id' => $brandId]];
    //     }
    //     if (isset($categoryIds)) {
    //         $must[] = ['terms' => ['category_id' => $categoryIds]];
    //     }
    //     if (isset($specificationValues) && count($specificationValues) > 0) {
    //         $specificationValues = array_map(function ($specificationValue) {
    //             return strtolower($specificationValue);
    //         }, $specificationValues);
    //         $must[] = [
    //             'nested' => [
    //                 'path' => 'specifications',
    //                 'query' => [
    //                     'terms' => ['specifications.specification_value.keyword' => $specificationValues]
    //                 ]

    //             ]

    //         ];
    //     }
    //     if ($sale == true) {
    //         $must[] = [
    //             'bool' => [
    //                 'should' => [
    //                     [
    //                         'nested' => [
    //                             'path' => 'skus',
    //                             'query' => [
    //                                 'bool' => [
    //                                     'must' => [
    //                                         ['match' => ['skus.default' => true]],
    //                                         ['range' => ["skus.{$memberType}_discount" => [
    //                                             'gt' => 0,
    //                                         ]]]
    //                                     ]
    //                                 ]
    //                             ]
    //                         ],


    //                     ],
    //                     [
    //                         'bool' => [
    //                             'must' => [
    //                                 [
    //                                     'exists' => [
    //                                         'field' => 'product_flash_sale_active',
    //                                     ]
    //                                 ],
    //                                 [
    //                                     'range' => ["product_flash_sale_active.start_time" => [
    //                                         'lte' => 'now',
    //                                     ]]
    //                                 ],
    //                                 [
    //                                     'range' => ["product_flash_sale_active.end_time" => [
    //                                         'gt' => 'now',
    //                                     ]]
    //                                 ],
    //                                 [
    //                                     'range' => ["product_flash_sale_active.discount" => [
    //                                         'gt' => 0,
    //                                     ]]
    //                                 ]
    //                             ]
    //                         ],
    //                     ]
    //                 ]
    //             ]
    //         ];
    //     }
    //     if ($new == true) {
    //         $daysThreshold = ProductService::DAYS_THRESHOLD;
    //         $must[] = [
    //             'range' => ['created_at' => [
    //                 'gte' => "now-{$daysThreshold}d/d" //Carbon::now()->subDays($daysThreshold)
    //             ]]
    //         ];
    //     }
    //     $skuDefault = [
    //         'nested' => [
    //             'path' => 'skus',
    //             'query' => [
    //                 'match' => ['skus.default' => true],
    //             ],
    //             'inner_hits' => new \stdClass(),
    //         ],
    //     ];
    //     if (isset($maxPrice) && $maxPrice > $minPrice) {
    //         $skuDefault['nested']['query']['bool']['must'] = [
    //             'script' => [
    //                 'script' => $this->buildScriptCalculatePrice2(
    //                     $memberType,
    //                     "(calculatedValue >= {$minPrice} && calculatedValue <= {$maxPrice})"
    //                 )
    //             ]
    //         ];
    //     }
    //     $must[] = $skuDefault;
    //     $body['query'] = [
    //         'bool' => [
    //             'must' => $must,
    //         ],
    //     ];
    //     $filedSort = '_score';
    //     $scriptSort['order'] = 'DESC';
    //     if ($sort != 'score') {
    //         // return $sort;
    //         $scriptSort = [];
    //         switch ($sort) {
    //             case 'hot':
    //                 $filedSort = 'total_quantity_sold';
    //                 $scriptSort['order'] = 'DESC';
    //                 break;
    //             case 'rating':
    //                 $filedSort = 'average_rating';
    //                 $scriptSort['order'] = 'DESC';
    //                 break;
    //             case 'az':
    //                 $filedSort = 'product_name.keyword';
    //                 $scriptSort['order'] = 'ASC';
    //                 break;
    //             case 'za':
    //                 $filedSort = 'product_name.keyword';
    //                 $scriptSort['order'] = 'DESC';
    //                 break;
    //             case 'p-asc':
    //                 $filedSort = '_script';
    //                 $scriptSort['order'] = 'ASC';
    //                 $scriptSort['type'] = 'number';
    //                 $scriptSort['script'] = $this->buildScriptCalculatePrice2($memberType, 'calculatedValue');
    //                 $scriptSort['mode'] = 'min';
    //                 break;
    //             case 'p-desc':
    //                 $filedSort = '_script';
    //                 $scriptSort['order'] = 'DESC';
    //                 $scriptSort['type'] = 'number';
    //                 $scriptSort['script'] = $this->buildScriptCalculatePrice2($memberType, 'calculatedValue');
    //                 $scriptSort['mode'] = 'max';
    //                 break;
    //             default:
    //                 # code...
    //                 break;
    //         }
    //         $body['sort'] = [
    //             $filedSort => $scriptSort
    //         ];
    //         // $body['score_mode'] = 'max';
    //     }
    //     // $body["runtime_mappings"] = [
    //     //     "seller_name_initial" => [
    //     //         "type" => "keyword",
    //     //         "script" => [
    //     //             "source" => "
    //     //                     if (doc['seller_name'].size() > 0) {
    //     //                         String name = doc['seller_name'].value;
    //     //                         emit(name.substring(0, 1).toUpperCase());
    //     //                     } else {
    //     //                         emit('');
    //     //                     }"
    //     //         ]
    //     //     ]
    //     // ];
    //     if ($loadFilerSellerAndSpecification == true) {
    //         $body['aggs'] = [
    //             'specifications' => [
    //                 'nested' => [
    //                     'path' => 'specifications'
    //                 ],
    //                 'aggs' => [
    //                     // "specification_value_group" => [
    //                     //     "terms" => [
    //                     //         "field" => "specifications.specification_value",
    //                     //         // "size" => 10 // Số lượng bucket tối đa trả về
    //                     //     ]
    //                     // ],
    //                     "specification_name_group" => [
    //                         "terms" => [
    //                             "field" => "specifications.specification_name.keyword",
    //                             // "size" => 10 // Số lượng bucket tối đa trả về
    //                         ],
    //                         'aggs' => [
    //                             'specification_values' => [
    //                                 "terms" => [
    //                                     "field" => "specifications.specification_value.keyword",
    //                                     // "size" => 10 // Số lượng bucket tối đa trả về
    //                                 ],
    //                                 'aggs' => [
    //                                     "specificationDetail" => [
    //                                         "top_hits" => [
    //                                             "_source" => [
    //                                                 "include" => [
    //                                                     'specifications.specification_value',
    //                                                 ]
    //                                             ],
    //                                             'size' => 1,
    //                                         ],
    //                                     ],
    //                                 ],
    //                             ]
    //                         ]
    //                     ]

    //                 ]
    //             ],
    //             'sellers' => [
    //                 'nested' => [
    //                     'path' => 'sellers'
    //                 ],
    //                 'aggs' => [
    //                     'seller_name_initial_group' => [
    //                         "terms" => [
    //                             "field" => "sellers.seller_name_initial",
    //                             "order" => [
    //                                 "_key" => "asc"  // Sắp xếp theo giá trị của trường 'sellers.seller_name_initial'
    //                             ]
    //                         ],
    //                         'aggs' => [
    //                             "sellers" => [
    //                                 "terms" => [
    //                                     "field" => "sellers.seller_name.keyword",
    //                                     "order" => [
    //                                         "_key" => "asc"
    //                                     ]
    //                                 ],
    //                                 'aggs' => [
    //                                     "sellerDetail" => [
    //                                         "top_hits" => [
    //                                             "_source" => [
    //                                                 "include" => [
    //                                                     'sellers.id',
    //                                                     'sellers.logo',
    //                                                 ]
    //                                             ],
    //                                             'size' => 1,
    //                                         ],
    //                                     ],
    //                                 ],
    //                             ],
    //                         ],
    //                     ],
    //                     // "seller_name_group" => [
    //                     //     "composite" => [
    //                     //         "sources" => [
    //                     //             ["seller_id" => ["terms" => ["field" => "sellers.id"]]],
    //                     //             ["seller_name" =>  ["terms" => ["field" => "sellers.seller_name.keyword"]]],
    //                     //             ["logo" => ["terms" => ["field" => "sellers.logo"]]]
    //                     //         ]
    //                     //     ],
    //                     //     'aggs' => [
    //                     //         'product_reverse_nested' => [
    //                     //             'reverse_nested' => new \stdClass(),
    //                     //         ]
    //                     //     ]
    //                     // ],
    //                 ]

    //             ],
    //         ];
    //     }

    //     $params = [
    //         'index' => $this->index,
    //         '_source' => [
    //             'id',
    //             'product_name',
    //             'cover_image',
    //             'shipping_point',
    //             'category_id',
    //             'brand_id',
    //             'created_at',
    //             'updated_at',
    //             'average_rating',
    //             'total_rating',
    //             'total_quantity_sold',
    //             'product_flash_sale_active.start_time',
    //             'product_flash_sale_active.end_time',
    //             'product_flash_sale_active.discount',
    //             'sellers.id',
    //             'sellers.seller_name',

    //         ],
    //         'body' => $body,
    //         // "scroll" => "1m",
    //         'from' => $from,
    //         'size' => $perPage,
    //         'track_total_hits' => true,
    //         // 'score_mode' => 'max'
    //     ];
    //     // return $params;
    //     $response = $this->elasticClient()->search($params);
    //     if ($response['hits']['total']['value'] == 0) {
    //         return ['products' => []];
    //     }
    //     $totalPage = ceil($response['hits']['total']['value'] / $perPage);
    //     return [
    //         'currentPage' => $page,
    //         'products' => $response['hits']['hits'],
    //         'aggregations' => $response['aggregations'] ?? null,
    //         'totalPage' => $totalPage,
    //         'lastPage' => $totalPage,
    //         'total' => $response['hits']['total']['value'],
    //     ];
    // }
    protected function buildScriptCalculatePrice2($memberType, $returnString)
    {
        return [
            'source' => "
                double discount = doc['skus.{$memberType}_discount'].size() > 0 ? doc['skus.{$memberType}_discount'].value : 0;
                long now = new Date().getTime();
                double originalPrice = doc['skus.{$memberType}_price'].size() > 0 ? doc['skus.{$memberType}_price'].value : 0;

                if (doc['product_flash_sale_active.start_time'].size() > 0 && doc['product_flash_sale_active.end_time'].size() > 0) {
                    long startTime = doc['product_flash_sale_active.start_time'].value.toInstant().toEpochMilli();
                    long endTime = doc['product_flash_sale_active.end_time'].value.toInstant().toEpochMilli();
                    long nowInSeconds = System.currentTimeMillis();

                    if (startTime <= nowInSeconds && endTime > nowInSeconds) {
                        discount = Math.min(discount + doc['product_flash_sale_active.discount'].value, 1.0);
                    }
                }
                
                double calculatedValue = originalPrice * (1 - discount);
                return {$returnString};
            ",
            'lang' => 'painless',
            // 'params'=>[
            //     'product_flash_sale_active' => 
            // ]
        ];
    }




    public function search(string $txtSearch = null, bool $fz = true,  int $page = 1, array $categoryIds = null, int $brandId = null, string $sort = 'score', bool $sale = false, bool $new = false, int  $minPrice = 0, int  $maxPrice = null, array $sellerIds = null, array $specificationValues = null, bool $searchBySku = false, bool $loadFilerSellerAndSpecification = false)
    {
        $perPage = ProductService::PER_PAGE;
        $memberType = Helper::getMemberType();
        $from = $perPage * ($page - 1);
        $must = [];
        if (isset($sellerIds) && count($sellerIds) > 0) {
            $must[] = [
                'nested' => [
                    'path' => 'sellers',
                    'query' => [
                        'terms' => ['sellers.id' => $sellerIds]
                    ]
                ]

            ];
        }
        if (isset($txtSearch)) {
            $boolTextSearch = [
                'should' => [
                    [
                        'match' => ['product_name' => [
                            'query' => $txtSearch,
                            'boost' => 10
                        ]],
                    ],
                    // [
                    //     'match' => ['describe' => [
                    //         'query' => $txtSearch,
                    //         'boost' => 1
                    //     ]],
                    // ],
                    // ['match' => ['detail' => [
                    //     'query' => $txtSearch,
                    //     'boost' => 1
                    // ]]],
                ]
            ];
            if ($fz == true) {
                $boolTextSearch['should'][] =  [
                    'fuzzy' => ['product_name' => [
                        'value' => $txtSearch,
                        'fuzziness' => 'AUTO'
                    ]]
                ];
                // ['match' => ['product_name' => $txtSearch]],
            }
            $must[] = ['bool' => $boolTextSearch];
        }
        if (isset($brandId)) {
            $must[] = ['match' => ['brand_id' => $brandId]];
        }
        if (isset($categoryIds)) {
            $must[] = ['terms' => ['category_id' => $categoryIds]];
        }
        if (isset($specificationValues) && count($specificationValues) > 0) {
            $specificationValues = array_map(function ($specificationValue) {
                return strtolower($specificationValue);
            }, $specificationValues);
            $must[] = [
                'nested' => [
                    'path' => 'specifications',
                    'query' => [
                        'terms' => ['specifications.specification_value.keyword' => $specificationValues]
                    ]

                ]

            ];
        }
        if ($sale == true) {
            $must[] = [
                'bool' => [
                    'should' => [
                        [
                            'nested' => [
                                'path' => 'skus',
                                'query' => [
                                    'bool' => [
                                        'must' => [
                                            ['match' => ['skus.default' => true]],
                                            ['range' => ["skus.{$memberType}_discount" => [
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
                                            'field' => 'product_flash_sale_active',
                                        ]
                                    ],
                                    [
                                        'range' => ["product_flash_sale_active.start_time" => [
                                            'lte' => 'now',
                                        ]]
                                    ],
                                    [
                                        'range' => ["product_flash_sale_active.end_time" => [
                                            'gt' => 'now',
                                        ]]
                                    ],
                                    [
                                        'range' => ["product_flash_sale_active.discount" => [
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
                'range' => ['created_at' => [
                    'gte' => "now-{$daysThreshold}d/d" //Carbon::now()->subDays($daysThreshold)
                ]]
            ];
        }

        $skuDefault = [
            'nested' => [
                'path' => 'skus',
                // 'query'=> [
                //     'bool'=> new \stdClass(),
                // ]
            ],
        ];
        $buildQueryMustPrice = [];
        if ($searchBySku == false) {
            $buildQueryMustPrice[] = [
                'match' => ['skus.default' => true]
            ];
        }
        if (isset($maxPrice) && $maxPrice >= $minPrice) {
            $buildQueryMustPrice[] = [
                'script' => [
                    'script' => $this->buildScriptCalculatePrice2(
                        $memberType,
                        "(calculatedValue >= {$minPrice} && calculatedValue <= {$maxPrice})"
                    )
                ]

            ];
        }
        $checkFilterPrice = count($buildQueryMustPrice) > 0;
        if ($checkFilterPrice == false) {
            $skuDefault['nested']['query']['match_all'] = new \stdClass();
        } else {
            $skuDefault['nested']['query']['bool']['must'] = $buildQueryMustPrice;
        }
        $skuDefault['nested']['inner_hits']  = new \stdClass();
        $must[] = $skuDefault;
        $body['query'] = [
            'bool' => [
                'must' => $must,
            ],
        ];
        // $params = [];
        if ($sort != 'score') {
            // return $sort;
            $scriptSort = [];
            switch ($sort) {
                case 'hot':
                    $scriptSort = [
                        'total_quantity_sold' => [
                            'order' => 'DESC'
                        ]
                    ];
                    break;
                case 'rating':
                    $scriptSort = [
                        'average_rating' => [
                            'order' => 'DESC'
                        ]
                    ];
                    break;
                case 'az':
                    $scriptSort = [
                        'product_name.keyword' => [
                            'order' => 'ASC'
                        ]
                    ];
                    break;
                case 'za':
                    // $filedSort = ;
                    $scriptSort = [
                        'product_name.keyword' => [
                            'order' => 'DESC'
                        ]
                    ];
                    break;
                default:
                    $scriptSort = [
                        '_script' => [
                            'type' => 'number',
                            'script' => $this->buildScriptCalculatePrice2($memberType, 'calculatedValue'),
                            'nested' => [
                                'path' => 'skus',
                                // 'filter' => [
                                //     'bool' => [
                                //         'must' => 
                                //     ]
                                // ],
                                'filter' => [
                                    'term' => [
                                        'skus.default' => true,
                                    ],
                                ],
                                'max_children' => 1,
                            ],

                        ],

                    ];
                    if ($sort == 'p-asc') {
                        $scriptSort['_script']['mode'] = 'min';
                        $scriptSort['_script']['order'] = 'ASC';
                    } else {
                        $scriptSort['_script']['mode'] = 'max';
                        $scriptSort['_script']['order'] = 'DESC';
                    }
                    // case 'p-asc':    
                    // case 'p-desc':
                    // $scriptSort['order'] = 'ASC';
                    // $scriptSort['type'] = 'number';
                    // $scriptSort['nested']['path'] = 'skus';
                    // $scriptSort['script'] = $this->buildScriptCalculatePrice2($memberType, 'calculatedValue');
                    // $scriptSort['mode'] = 'min';
                    // break;

                    // $filedSort = '_script';
                    // $scriptSort['order'] = 'DESC';
                    // $scriptSort['type'] = 'number';
                    // $scriptSort['script'] = $this->buildScriptCalculatePrice2($memberType, 'calculatedValue');
                    // $scriptSort['mode'] = 'max';
            }
            $body['sort'] = $scriptSort;
            // $body['sort'] = [
            //     $filedSort => $scriptSort
            // ];
        }
        // $body["runtime_mappings"] = [
        //     "seller_name_initial" => [
        //         "type" => "keyword",
        //         "script" => [
        //             "source" => "
        //                     if (doc['seller_name'].size() > 0) {
        //                         String name = doc['seller_name'].value;
        //                         emit(name.substring(0, 1).toUpperCase());
        //                     } else {
        //                         emit('');
        //                     }"
        //         ]
        //     ]
        // ];
        if ($loadFilerSellerAndSpecification == true) {
            $body['aggs'] = [
                'specifications' => [
                    'nested' => [
                        'path' => 'specifications'
                    ],
                    'aggs' => [
                        // "specification_value_group" => [
                        //     "terms" => [
                        //         "field" => "specifications.specification_value",
                        //         // "size" => 10 // Số lượng bucket tối đa trả về
                        //     ]
                        // ],
                        "specification_name_group" => [
                            "terms" => [
                                "field" => "specifications.specification_name.keyword",
                                "size" => 10000 // Số lượng bucket tối đa trả về
                            ],
                            'aggs' => [
                                'specification_values' => [
                                    "terms" => [
                                        "field" => "specifications.specification_value.keyword",
                                        // "size" => 10 // Số lượng bucket tối đa trả về
                                        "size" => 10000
                                    ],
                                    'aggs' => [
                                        "specificationDetail" => [
                                            "top_hits" => [
                                                "_source" => [
                                                    "include" => [
                                                        'specifications.specification_value',
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
                ],
                'sellers' => [
                    'nested' => [
                        'path' => 'sellers'
                    ],
                    'aggs' => [
                        'seller_name_initial_group' => [
                            "terms" => [
                                "field" => "sellers.seller_name_initial",
                                "size" => 1000,
                                "order" => [
                                    "_key" => "asc"  // Sắp xếp theo giá trị của trường 'sellers.seller_name_initial'
                                ]
                            ],
                            'aggs' => [
                                "sellers" => [
                                    "terms" => [
                                        "field" => "sellers.seller_name.keyword",
                                        "size" => 10000,
                                        "order" => [
                                            "_key" => "asc"
                                        ]
                                    ],
                                    'aggs' => [
                                        "sellerDetail" => [
                                            "top_hits" => [
                                                "_source" => [
                                                    "include" => [
                                                        'sellers.id',
                                                        'sellers.logo',
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

                ],
            ];
        }

        $params = [
            'index' => $this->index,
            '_source' => [
                'id',
                'product_name',
                'cover_image',
                'shipping_point',
                'category_id',
                'brand_id',
                'created_at',
                'updated_at',
                'average_rating',
                'total_rating',
                'total_quantity_sold',
                'product_flash_sale_active.start_time',
                'product_flash_sale_active.end_time',
                'product_flash_sale_active.discount',
                'sellers.id',
                'sellers.seller_name',
                'specifications.id',
                'specifications.specification_name',
                'specifications.specification_value',

            ],
            'body' => $body,
            // "scroll" => "1m",
            'from' => $from,
            'size' => $perPage,
            'track_total_hits' => true,
        ];
        // return $params;
        $response = $this->elasticClient()->search($params);
        if ($response['hits']['total']['value'] == 0) {
            return ['products' => []];
        }
        $totalPage = ceil($response['hits']['total']['value'] / $perPage);
        return [
            'currentPage' => $page,
            'products' => $response['hits']['hits'],
            'aggregations' => $response['aggregations'] ?? null,
            'totalPage' => $totalPage,
            'lastPage' => $totalPage,
            'total' => $response['hits']['total']['value'],
        ];
    }
}
