<?php
namespace Modules\Order\Repositories\Interface;

use App\Repositories\RepositoryInterface;

interface DiscountCouponRepositoryInterface extends RepositoryInterface
{
    public function getByCouponCodeStillInUse($couponCode);
    public function findByCouponCode($couponCode);
}
