<?php

namespace Modules\Elastic\Services;

use Elastic\Elasticsearch\ClientBuilder;
use Modules\Product\Services\ProductService;

class BrandElasticService extends BaseElasticService
{
    protected $fieldSuggest = 'brand_name';
    protected $keySuggest = 'brand_name.suggest';
    public function getProperties()
    {
        return [
            'logo' => ['type' => 'text'],
            'brand_name' => [
                'type' => 'text',
                'fields' => [
                    'suggest' => [
                        "type" => "completion",
                        'preserve_separators'=>false
                    ]
                ]
            ],
            'total_purchases' => ['type' => 'integer'],
            'total_review' => ['type' => 'integer'],
            'created_at' => ['type' => 'date'],
            'updated_at' => ['type' => 'date'],
            // $this->keySuggest => [
            //     'type' => 'completion',
            // ]
        ];
    }
    protected function getModel(): string
    {
        return \Modules\Product\Entities\Brand::class;
    }
}
