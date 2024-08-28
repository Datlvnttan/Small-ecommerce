<?php

namespace Modules\Order\Services;

use Modules\Order\Repositories\Interface\OrderDetailRepositoryInterface;


class OrderDetailService
{
    protected $orderDetailRepositoryInterface;
    public function __construct(OrderDetailRepositoryInterface $orderDetailRepositoryInterface)
    {
        $this->orderDetailRepositoryInterface = $orderDetailRepositoryInterface;
    }
}
