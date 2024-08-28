<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Sku extends Model
{
    use HasFactory;

    public $table = 'skus';
    protected $fillable = [
        'product_id',
        'guest_price',
        'guest_discount',
        'member_retail_price',
        'member_retail_discount',
        'member_wholesale_price',
        'member_wholesale_discount',
        'default',
        'quantity',
        'option',
        'product_part_number'
    ];
    protected $casts = [
        'default' => 'boolean',
        // 'quantity' => 'integer',
    ];

    // protected $hidden = [
    //     'member_retail_price',
    //     'member_retail_discount',
    //     'member_wholesale_price',
    //     'member_wholesale_discount',
    //     'guest_price',
    //     'guest_discount',
    // ];
    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\SkuFactory::new();
    }
    public function skuProductAttributeOptions()
    {
        return $this->hasMany(SkuProductAttributeOption::class, 'product_attribute_option_id');
    }
    public function productAttributeOptions()
    {
        return $this->hasManyThrough(ProductAttributeOption::class, SkuProductAttributeOption::class, 'sku_id', 'product_attribute_option_id', 'id', 'product_attribute_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    // Định nghĩa Accessor cho thuộc tính age
    public function getPriceOldAttribute()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (isset($user->member_type)) {
                return $this->getAttribute("member_{$user->member_type}_price");
            }
        }
        return $this->guest_price;
    }
    // public function getDiscountAttribute()
    // {
    //     if (Auth::check()) {
    //         $user = Auth::user();
    //         if (isset($user->member_type)) {
    //             return $this->getAttribute("member_{$user->member_type}_discount");
    //         }
    //     }
    //     return $this->guest_discount;
    // }

    // Đảm bảo thuộc tính tùy chỉnh được thêm vào khi model được chuyển đổi thành mảng hoặc JSON
    // protected $appends = ['price_old', 'discount', 'price_new', 'discount_percent'];
    public function getDiscountPercentAttribute()
    {
        return $this->discount * 100;
    }
    public static function getOptions($productPartNumber) : array|null
    {
        if(isset($productPartNumber))
        {
            return array_map('intval',explode('-', $productPartNumber));
        }
        return null;
        
    }
    public function getOptionsName() : string
    {
        $options = self::getOptions($this->product_part_number);
        if (isset($options)) {
            $optionNames = ProductAttributeOption::whereIn('id', $options)->pluck('option_name')->toArray();
            return implode(', ', $optionNames);
        }
        return '';
    }
}
