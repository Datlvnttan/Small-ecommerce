<?php

namespace Modules\Order\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\DB;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Repositories\Interface\OrderRepositoryInterface;

class OrderRepository extends EloquentRepository implements OrderRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Order\Entities\Order::class;
    }
    public function getByOrderKey($orderKey)
    {
        return $this->model->where('order_key', $orderKey)->first();
    }
    public function getPersonalOrdersByUserIdAndStatus(int $userId, int $perPage, OrderStatus $status = null)
    {
        $query = $this->model->with(['orderDetails' => function ($query) {
            $query->with(['sku' => function ($query) {
                $query->with('product');
            }]);
        }])->where('user_id', $userId);
        if (isset($status)) {
            $query = $this->buildQueryStatus($query,$status);
        }
        // return $query;
        return $query->orderBy('created_at','DESC')->paginate($perPage);
    }
    public function getOrderDetailsByOrderId($orderId)
    {
        return $this->model->with(['orderDetails' => function ($query) {
            $query->with(['sku' => function ($query) {
                $query->with('product');
            }]);
        }])->where('id', $orderId)->first();
    }
    protected function buildQueryStatus($query, OrderStatus $status)
    {
        $query = $query->whereRaw("JSON_CONTAINS_PATH(status, 'one', \"$.{$status->value}\")");
        if($status === OrderStatus::Cancelled || $status === OrderStatus::Rejected)
        {
            return $query;
        }
        else
        {

            $nextStatus = OrderStatus::getTheNextStatus($status);
            $cancelledValue = OrderStatus::Cancelled->value;
            $rejectedValue = OrderStatus::Rejected->value;
            if(isset($nextStatus))
            {
                $query = $query->whereRaw("NOT JSON_CONTAINS_PATH(status, 'one', \"$.{$nextStatus->value}\")")
                ->whereRaw("NOT JSON_CONTAINS_PATH(status, 'one', \"$.{$cancelledValue}\")")
                ->whereRaw("NOT JSON_CONTAINS_PATH(status, 'one', \"$.{$rejectedValue}\")");
            }
        }
        return $query;
    }
}
