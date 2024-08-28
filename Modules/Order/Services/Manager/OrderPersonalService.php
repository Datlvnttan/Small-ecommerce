<?php

namespace Modules\Order\Services\Manager;

use Modules\Order\Enums\OrderStatus;
use Modules\Order\Repositories\Interface\OrderRepositoryInterface;


class OrderPersonalService
{
    public const PER_PAGE_ORDER_HISTORY = 5;
    protected $orderRepositoryInterface;
    public function __construct(OrderRepositoryInterface $orderRepositoryInterface)
    {
        $this->orderRepositoryInterface = $orderRepositoryInterface;
    }

    public function getAllPersonalOrderHistories($userId,$statusName='all')
    {
        $status = OrderStatus::tryFrom($statusName);
        // return $status;
        // if(!isset($status))
        //     $status = 'all';
        return $this->orderRepositoryInterface->getPersonalOrdersByUserIdAndStatus($userId,OrderPersonalService::PER_PAGE_ORDER_HISTORY,$status);
    }
}
