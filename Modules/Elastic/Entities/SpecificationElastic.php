<?php

namespace Modules\Elastic\Entities;


class SpecificationElastic extends ElasticModel
{
    public static function getProperties()
    {
        return [
            'id' => ['type' => 'keyword'],
            'specification_name' => [
                'type' => 'text',
                'fields' => [
                    'keyword' => [
                        'type' => 'keyword',
                        "normalizer"=> "lowercase"
                    ]
                ]
            ],
            'specification_value' => [
                'type' => 'text',
                'fields' => [
                    'keyword' => [
                        'type' => 'keyword',
                        "normalizer"=> "lowercase"
                    ]
                ]
            ],
            'product_id' => ['type' => 'keyword'],
            'created_at' => ['type' => 'date'],
            'updated_at' => ['type' => 'date'],
            // $this->keySuggest => [
            //     'type' => 'completion',
            // ]
        ];
    }
}
