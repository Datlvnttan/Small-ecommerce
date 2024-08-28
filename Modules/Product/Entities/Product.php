<?php

namespace Modules\Product\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\Product\Services\ProductService;
use Modules\Seller\Entities\Seller;
use Modules\Seller\Entities\SellerProduct;

class Product extends Model
{
    use HasFactory;
    public const QUANTITY_HOT = 10000;
    public $table = 'products';

    protected $fillable = [
        'product_name',
        'describe',
        'detail',
        'brand_id',
        'category_id',
        'cover_image',
        'average_rating',
        'total_rating',
        'shipping_point',
        'created_at',
    ];
    // protected $appends = ['is_new', 'is_hot'];
    protected $casts = [
        'average_rating' => 'double',
        'total_rating' => 'integer',
        'product_name' => 'string',
        'cover_image' => 'string',
        'describe' => 'string',
        'detail' => 'string',
        'shipping_point' => 'integer',
        'category_id' => 'integer',
        'brand_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductFactory::new();
    }
    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }
    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function productFlashSaleActive()
    {
        return $this->hasOne(ProductFlashSale::class, 'product_id')
            ->join('flash_sales', 'flash_sales.id', '=', 'product_flash_sales.flash_sale_id')
            ->whereBetween(DB::raw('CURRENT_TIME'), [DB::raw('flash_sales.start_time'), DB::raw('flash_sales.end_time')]);
    }
    public function flashSale()
    {
        return $this->hasOneThrough(FlashSale::class, ProductFlashSale::class, 'product_id', 'id', 'id', 'flash_sale_id')
            ->whereBetween(DB::raw('CURRENT_TIME'), [DB::raw('flash_sales.start_time'), DB::raw('flash_sales.end_time')]);
    }
    // public function skus()
    // {
    //     return $this->hasMany(Sku::class, 'product_id');
    // }
    public function skuDefault()
    {
        return $this->hasOne(Sku::class, 'product_id')->where('default', true);
        // if (isset($callback)) {
        //     return $callback($query);
        // }
        // return $query->where('default', true);
    }
    public static function isNew($createAd)
    {
        if (isset($createAd)) {
            return Carbon::parse($createAd)->diffInDays(now()) <= ProductService::DAYS_THRESHOLD;
        }
        return null;
    }
    // public function getIsNewAttribute()
    // {
    //     return self::isNew($this->created_at);
    // }
    // public function getIsHotAttribute()
    // {
    //     return self::isHot($this->total_quantity_sold);
    // }
    public static function isHot($totalQuantity)
    {
        return $totalQuantity >= self::QUANTITY_HOT;
    }

    public function scopeWhereIsNew($query)
    {
        $newThresholdDate = ProductService::DAYS_THRESHOLD;
        return $query->whereRaw("DATEDIFF(CURRENT_DATE, {$this->table}.created_at) <= {$newThresholdDate}");
    }

    public function scopeWhereIsHot($query)
    {
        $quantityHot = self::QUANTITY_HOT;
        return $query->where('total_quantity_sold', '>=', $quantityHot);
    }
    public function skus()
    {
        return $this->hasMany(Sku::class, 'product_id');
    }
    public function sellerProducts()
    {
        return $this->hasMany(SellerProduct::class, 'product_id');
    }
    public function sellers()
    {
        return $this->hasManyThrough(Seller::class, SellerProduct::class, 'product_id', 'id', 'id','seller_id');
    }
    public function specifications()
    {
        return $this->hasMany(Specification::class, 'product_id');
    }
}
