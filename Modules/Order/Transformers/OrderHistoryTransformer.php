<?php

namespace Modules\Order\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

class OrderHistoryTransformer extends TransformerAbstract
{
    public function transform($orderHistory)
    {
        return [
            'current_page'=>$orderHistory->currentPage(), 
            // 'data'=>$this->includeOrders($orderHistory)['data'],
            'data'=>$orderHistory->getData(),
            'last_page'=>$orderHistory->lastPage(), 
        ];
    }
    public function includeOrders($orderHistory)
    {
        $fractal = new Manager();
        $result = $this->collection($orderHistory['data'], new OrderTransformer());
        return $fractal->createData($result)->toArray();
    }
}
