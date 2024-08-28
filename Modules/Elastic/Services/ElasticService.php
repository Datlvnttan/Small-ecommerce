<?php

namespace Modules\Elastic\Services;

use Modules\Product\Services\ProductService;


class ElasticService
{
    protected $elasticConnection;
    protected $modelElasticServices;
    public function __construct(ProductElasticService $productElasticService, CategoryElasticService $categoryElasticService, BrandElasticService $brandElasticService)
    {
        $this->modelElasticServices = [];
        // $this->modelElasticServices[] = $sellerElasticService;
        $this->modelElasticServices[] = $categoryElasticService;
        $this->modelElasticServices[] = $brandElasticService;
        $this->modelElasticServices[] = $productElasticService;
        $this->elasticConnection = ElasticConnectionService::instance();
    }
    public function syncDatabaseToElasticsearch()
    {
        foreach ($this->modelElasticServices as $modelElasticService) {
            $modelElasticService->syncDatabaseToElasticsearch();
        }
        return true;
    }
    public function suggest(string $txtSearch)
    {
        $body = [];
        // $indexArr = [];
        foreach ($this->modelElasticServices as $modelElasticService) {
            $index = $modelElasticService->getIndex();
            // array_push($indexArr, $index);
            $body[] = [
                'index' => $index
            ];
            $body[] = [
                'suggest' => [
                    'text' => $txtSearch,
                    $index => [
                        'prefix' => $txtSearch,
                        'completion' => [
                            'field' => $modelElasticService->getKeySuggest(),
                            'size' => 20,
                            'skip_duplicates' => true, //lọc bỏ các đề xuất trùng lặp
                            "fuzzy" => [ //có thể dùng "fuzzy":true nếu muốn giữ mặc định
                                "fuzziness" => 'AUTO:3,6', //số lượng kí tự thay đổi cho phép, Auto: tùy thuộc vào độ dài của kí tự mà sẽ có thay đổi khác nhau (df:Auto)
                                'transpositions' => true, //các kí tự thay đổi liền sau sẽ tính là 1 (df:true)
                                'min_length' => 3, //Nhừng từ có từ 3 kí tự trở lên mới được áp dụng mờ (df:3)
                                'prefix_length' => 1, //Từ thứ nhất sẽ không áp dụng mờ(df:1)
                                'unicode_aware' => true, //các phép đo(khoảng cách mờ, hoán đổi, độ dài) sẽ không được tính bằng điểm mã Unicode (df:false)
                            ]
                        ],
                    ],
                ],
                '_source' => $modelElasticService->getSourcesSuggest(),
            ];
        }
        // return $body;

        // return $params;
        $data = $this->elasticConnection->getElasticClient()->msearch([
            'body' => $body,
        ])->responses;
        // return $data;
        $suggestions = [];
        foreach ($data as $suggest) {
            foreach ($suggest->suggest as $key => $value) {
                $suggestions[$key] =  $value[0]->options;
            }
            // foreach ($indexArr as $idx) {
            //     if (isset($suggest->suggest->{'suggest_' . $idx})) {
            //         $suggestions['suggest_' . $idx] =  $suggest->suggest->{'suggest_' . $idx}[0]->options;
            //         // $suggestions = array_merge($suggestions, $suggest->suggest->{'suggest_' . $idx}[0]->options);
            //         break;
            //     }
            // }
        }
        return $suggestions;
    }

}
