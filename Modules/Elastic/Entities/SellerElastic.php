<?php

namespace Modules\Elastic\Entities;


class SellerElastic extends ElasticModel
{
    public static function getProperties()
    {
        return [
            'id' => ['type' => 'keyword'],
            'seller_name' => [
                'type' => 'text',
                "fields" => [
                    "keyword" => [
                        "type" => "keyword"
                    ],
                    'trigram' => [
                        'type' => "text",
                        'analyzer' => 'trigram'
                    ],
                    'suggest' => [
                        "type" => "completion",
                        'preserve_separators' => false
                    ]
                ],
            ],
            'email' => ['type' => 'keyword'],
            'logo' => ['type' => 'keyword'],
            'user_id' => ['type' => 'keyword'],
            'locked' => ['type' => 'boolean'],
            'created_at' => ['type' => 'date'],
            'updated_at' => ['type' => 'date'],
            'seller_name_initial' => [
                'type' => 'keyword',
            ],
            'products' => [
                'type' => 'nested',
                'dynamic' => false,
                'properties' => ProductElastic::getProperties()
            ]

        ];
    }
}
