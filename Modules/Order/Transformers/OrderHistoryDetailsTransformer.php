<?php

namespace Modules\Order\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Order\Entities\Order;

class OrderHistoryDetailsTransformer extends TransformerAbstract
{
    public function transform(Order $orderHistoryDetails)
    {
        return [
            // 
        ];
    }
}
