<?php

namespace Modules\Elastic\Entities;


class SkuElastic extends ElasticModel
{
    public static function getProperties()
    {
        return [
            'id' => ['type' => 'keyword'],
            'guest_price' => ['type' => 'integer'],
            'guest_discount' => ['type' => 'double'],
            'member_retail_price' => ['type' => 'integer'],
            'member_retail_discount' => ['type' => 'double'],
            'member_wholesale_price' => ['type' => 'integer'],
            'member_wholesale_discount' => ['type' => 'double'],
            'default' => ['type' => 'boolean'],
            'options' => [
                'type' => 'text',
                'fields' => [
                    'suggest' => [
                        "type" => "completion",
                        'preserve_separators' => false
                    ]
                ]
            ],
            'product_part_number' => ['type' => 'keyword'],
            'created_at' => ['type' => 'date'],
            'updated_at' => ['type' => 'date'],
            
        ];
    }
}
