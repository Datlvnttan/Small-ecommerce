<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductAttributeOption extends Model
{
    use HasFactory;

    public $table = 'product_attribute_options';

    protected $fillable = ['product_attribute_id','option_name'];
    
    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductAttributeOptionFactory::new();
    }
    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class,'product_attribute_id');
    }
}
