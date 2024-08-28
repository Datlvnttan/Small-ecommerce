<?php
namespace Modules\Order\Repositories\Interface;

use App\Repositories\RepositoryInterface;
use Modules\Order\Enums\OrderStatus;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function getByOrderKey($orderKey);
    // public function getOrderDetailsByOrderKey($orderKey);
    public function getPersonalOrdersByUserIdAndStatus(int $userId, int $perPage, OrderStatus $status = null);
    // public function getOrderDetailsByOrder($order);
    public function getOrderDetailsByOrderId($orderId);
}
