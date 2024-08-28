<?php

namespace Modules\Seller\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Entities\Product;

class Seller extends Model
{
    use HasFactory;
    public $table = 'sellers';

    protected $fillable = [
        'seller_name',
        'email',
        'logo',
        'user_id',
        'locked',
        'created_at',
    ];
    protected $casts = [
        'locked' => 'boolean',
    ];
    // protected static function boot()
    // {
    //     parent::boot();

    // }
    protected static function newFactory()
    {
        return \Modules\Seller\Database\factories\SellerFactory::new();
    }
    public function products()
    {
        return $this->hasManyThrough(Product::class, SellerProduct::class, 'seller_id', 'id', 'id', 'product_id');
    }
    public function sellerProducts()
    {
        return $this->hasMany(SellerProduct::class, 'seller_id');
    }
    protected $appends = ['seller_name_initial'];
    public function getSellerNameInitialAttribute()
    {
        try {
            $sellerName = $this->getAttribute('seller_name');

            if (empty($sellerName) || !is_string($sellerName)) {
                $sellerName = '';
            } else {
                $sellerName = mb_convert_encoding($sellerName, 'UTF-8', 'auto');
            }

            $firstTwoChars = mb_substr($sellerName, 0, 1, 'UTF-8');
            $uppercasedChars = mb_strtoupper($firstTwoChars, 'UTF-8');
            return $uppercasedChars;
        } catch (\Exception $e) {
            return null;
        }

        // 
    }
}
