<?php

namespace Modules\Order\Services\Manager;

use Modules\Order\Enums\OrderStatus;
use Modules\Order\Repositories\Interface\OrderRepositoryInterface;


class OrderAdminService
{
    protected $orderRepositoryInterface;
    public function __construct(OrderRepositoryInterface $orderRepositoryInterface)
    {
        $this->orderRepositoryInterface = $orderRepositoryInterface;
    }
    public function updateNextStatus($orderId)
    {
        $order = $this->orderRepositoryInterface->find($orderId);
        $nextStatus = OrderStatus::getTheNextStatus($order->current_status);
        if($nextStatus == null)
        {
            return false; // No next status found
        }
        $order->status = $nextStatus;
        $order->save();
        return true;
    }
}
