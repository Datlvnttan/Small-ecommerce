<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;
    public $table = 'brands';
    protected $fillable = [
        'id',
        'logo',
        'brand_name',
        'total_purchases',
        'total_review',
    ];

    protected $casts = [
        'logo'=>'string',
        'brand_name'=>'string',
        'total_purchases'=>'integer',
        'total_review'=>'integer',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\BrandFactory::new();
    }
}
