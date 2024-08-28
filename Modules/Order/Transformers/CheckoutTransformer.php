<?php

namespace Modules\Order\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Modules\Cart\Transformers\CartTransformer;

class CheckoutTransformer extends TransformerAbstract
{
    public function transform($checkout)
    {
        return [
            'createdAt' => $checkout['createdAt'],
            'orderDetails' => $this->includeCartItems($checkout)['data'],
            'shippingMethodId' => $checkout['shippingMethodId'],
        ];
    }
    public function includeCartItems($checkout)
    {
        $fractal = new Manager();
        $result = $this->collection($checkout['orderDetails'], new CartTransformer());
        return $fractal->createData($result)->toArray();
    }
}
