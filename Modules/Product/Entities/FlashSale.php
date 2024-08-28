<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlashSale extends Model
{
    use HasFactory;

    public $table = 'flash_sales';
    protected $fillable = [];
    protected $casts = [
        'id'=>'integer',
        'product_id'=>'integer',
        'flash_sale_id'=>'integer',
        'discount'=>'double',
        'created_at'=>'datetime',
        'updated_at'=>'datetime',
        'start_time'=>'datetime',
        'end_time'=>'datetime',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\FlashSaleFactory::new();
    }
}
