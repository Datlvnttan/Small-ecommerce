<?php

namespace Modules\Elastic\Entities;


class ProductElastic extends ElasticModel
{
    protected array $specifications;

    public static function getProperties()
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
                        'preserve_separators' => false
                    ]
                ],
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
}
