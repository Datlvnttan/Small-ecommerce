<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscountCoupon extends Model
{
    use HasFactory;

    public $table = 'discount_coupons';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Order\Database\factories\DiscountCouponFactory::new();
    }
}
