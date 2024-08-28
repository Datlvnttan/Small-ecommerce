<?php

namespace Modules\Order\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Order\Entities\DiscountCoupon;

class DiscountCouponTransformer extends TransformerAbstract
{
    public function transform(DiscountCoupon $discountCoupon)
    {
        return [
            'coupon_code'=>$discountCoupon->coupon_code,
            'discount'=>$discountCoupon->discount,
            'discount_percent'=>$discountCoupon->discount*100,
            'quantity'=>$discountCoupon->quantity,
        ];
    }
}
