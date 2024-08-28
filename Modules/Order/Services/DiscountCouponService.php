<?php

namespace Modules\Order\Services;

use Modules\Order\Repositories\Interface\DiscountCouponRepositoryInterface;
use Modules\Order\Repositories\Interface\OrderDetailRepositoryInterface;


class DiscountCouponService
{
    protected $discountCouponRepositoryInterface;
    public function __construct(DiscountCouponRepositoryInterface $discountCouponRepositoryInterface)
    {
        $this->discountCouponRepositoryInterface = $discountCouponRepositoryInterface;
    }
    public function getByCouponCode($couponCode)
    {
        return $this->discountCouponRepositoryInterface->getByCouponCodeStillInUse($couponCode);
    }
    public function updateInventoryByCouponCode($couponCode,bool $add = true, int $quantity = 1)
    {
        $discountCoupon = $this->discountCouponRepositoryInterface->findByCouponCode($couponCode);
        if (!isset($discountCoupon)) {
            return null;
        }
        if ($add) {
            $discountCoupon->quantity += $quantity;
        } else {
            $discountCoupon->quantity -= $quantity;
        }
        $discountCoupon->save();
    }
    
}
