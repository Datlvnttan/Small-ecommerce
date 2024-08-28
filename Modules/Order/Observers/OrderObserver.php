<?php

namespace Modules\Order\Observers;

use Illuminate\Support\Facades\Log;
use \Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatus;

class OrderObserver
{
    // protected $orderService;
    // public function __construct(\Modules\Order\Services\OrderService $orderService)
    // {
    //     $this->orderService = $orderService;
    // }
    protected $skuService;
    protected $discountCouponService;
    protected $mailService;
    public function __construct(\Modules\Product\Services\SkuService $skuService,
    \Modules\Order\Services\DiscountCouponService $discountCouponService,
    \Modules\Mailer\Services\MailService $mailService)
    {
        $this->skuService = $skuService;
        $this->discountCouponService = $discountCouponService;
        $this->mailService = $mailService;
    }
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Log::info("akhgdasjhgdadgasdhasdhjasdhjadghjagdjhgdasd");
        // if ($order->is_paid === true) {
        //     $this->skuService->updateInventoryFromOrderItems($order->id, false);
        // }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        $newStatus = $order->current_status;
        $originalStatus = $order->getOriginal('status');
        $originalStatus = Order::getStatus($originalStatus);
        if($newStatus === $originalStatus) 
        {
            return;
        }
        if ($newStatus === OrderStatus::Processing && ($originalStatus === OrderStatus::AwaitingConfirmation || $originalStatus === OrderStatus::AwaitingVerification)) {
            $this->skuService->updateInventoryFromOrderItems($order->id, false);
        }
        elseif ($newStatus === OrderStatus::Cancelled && $originalStatus === OrderStatus::Processing) {
            $originalDiscountCoupon = $order->discount_coupon;
            if($originalDiscountCoupon != null && isset($originalDiscountCoupon))
            {
                Log::info($originalDiscountCoupon);
                $this->discountCouponService->updateInventoryByCouponCode($originalDiscountCoupon->coupon_code);
            }
            $this->skuService->updateInventoryFromOrderItems($order->id);
        }
        $this->mailService->sendNotificationOrderStatusEmail($order);
    }
    /**
     * Handle the Order "updating" event.
     */
    public function updating(Order $order)
    {
        
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
