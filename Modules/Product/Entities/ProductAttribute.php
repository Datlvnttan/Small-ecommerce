<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductAttribute extends Model
{
    use HasFactory;

    public $table = 'product_attributes';
    protected $fillable = ['product_id','attribute_id'];
    
    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductAttributeFactory::new();
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }
    public function productAttributeOptions()
    {
        return $this->hasMany(ProductAttributeOption::class, 'product_attribute_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
