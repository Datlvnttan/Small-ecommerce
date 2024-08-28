<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductFlashSale extends Model
{
    use HasFactory;

    public $table = 'product_flash_sales';
    protected $fillable = [
        'product_id',
        'flash_sale_id',
        'discount',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductFlashSaleFactory::new();
    }
    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class, 'flash_sale_id');
    }
}
