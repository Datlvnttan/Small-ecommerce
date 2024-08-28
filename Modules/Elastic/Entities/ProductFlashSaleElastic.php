<?php

namespace Modules\Elastic\Entities;


class ProductFlashSaleElastic extends ElasticModel
{
    public static function getProperties()
    {
        return [
            'id' => ['type' => 'keyword'],
            'discount' => ['type' => 'float'],
            'created_at' => ['type' => 'date'],
            'updated_at' => ['type' => 'date'],
            'start_time' => [
                'type' => 'date',
                'format' => "yyyy-MM-dd HH:mm:ss||yyyy/MM/dd'T'HH:mm:ss",
                "null_value"=>"1970-01-01 00:00:00"
            ],
            'end_time' => [
                'type' => 'date',
                'format' => "yyyy-MM-dd HH:mm:ss||yyyy/MM/dd'T'HH:mm:ss",
                "null_value"=>"1970-01-01 00:00:00"
            ],
        ];
    }
}
