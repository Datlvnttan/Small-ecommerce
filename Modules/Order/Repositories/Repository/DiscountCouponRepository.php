<?php

namespace Modules\Order\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Modules\Order\Repositories\Interface\DiscountCouponRepositoryInterface;


class DiscountCouponRepository extends EloquentRepository implements DiscountCouponRepositoryInterface 
{
    public function getModel()
    {
        return \Modules\Order\Entities\DiscountCoupon::class;
    }
    public function getByCouponCodeStillInUse($couponCode)
    {
        return $this->model
        ->where('coupon_code', $couponCode)
        ->where('quantity','>',0)
        ->first();
    }
    public function findByCouponCode($couponCode)
    {
        return $this->model
        ->where('coupon_code', $couponCode)
        ->first();
    }
}
