<?php

namespace Modules\Elastic\Services;

use Elastic\Elasticsearch\ClientBuilder;
use Modules\Product\Services\ProductService;

class CategoryElasticService extends BaseElasticService
{
    protected $fieldSuggest = 'category_name';
    protected $keySuggest = 'category_name.suggest';
    public function getProperties()
    {
        return [
            'id' => ['type' => 'keyword'],
            'category_name' => [
                'type' => 'text',
                'fields' => [
                    'suggest' => [
                        "type" => "completion",
                        'preserve_separators'=>false
                    ]
                ]
            ],
            'parent_category_id' => ['type' => 'keyword'],
            'created_at' => ['type' => 'date'],
            'updated_at' => ['type' => 'date'],
            // $this->keySuggest => [
            //     'type' => 'completion',
            // ]
        ];
    }
    protected function getModel(): string
    {
        return \Modules\Product\Entities\Category::class;
    }
}
