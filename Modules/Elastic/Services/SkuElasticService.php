<?php

namespace Modules\Elastic\Services;

use Elastic\Elasticsearch\ClientBuilder;
use Modules\Product\Services\ProductService;

class SkuElasticService extends BaseElasticService
{
    public function getProperties()
    {
        return [
            'id' => ['type' => 'keyword'],
            'product_id' => ['type' => 'keyword'],
            'guest_price' => ['type' => 'integer',],
            'guest_discount' => ['type' => 'double',],
            'member_retail_price' => ['type' => 'integer',],
            'member_retail_discount' => ['type' => 'double',],
            'member_wholesale_price' => ['type' => 'integer',],
            'member_wholesale_discount' => ['type' => 'double',],
            'default' => ['type' => 'boolean',],
            'product_part_number ' => ['type' => 'keyword',],
            'options ' => ['type' => 'text',],   
            'quantity ' => ['type' => 'integer',],
            'created_at' => ['type' => 'date'],
            'updated_at' => ['type' => 'date'],   
            'suggest' => [
                'type' => 'completion',
            ]
        ];
    }
    protected function getModel(): string
    {
        return \Modules\Product\Entities\Sku::class;
    }
}
