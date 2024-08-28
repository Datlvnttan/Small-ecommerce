<?php

namespace Modules\Elastic\Entities;


class BrandElastic extends ElasticModel
{
    public static function getProperties()
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
}
