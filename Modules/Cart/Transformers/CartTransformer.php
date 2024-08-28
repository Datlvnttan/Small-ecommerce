<?php

namespace Modules\Cart\Transformers;

use App\Helpers\Helper;
use League\Fractal\TransformerAbstract;
use Modules\Cart\Entities\Cart;
use Modules\Product\Transformers\BaseProductTransformer;

class CartTransformer extends BaseProductTransformer
{
    protected function additionalTransform($cartItem)
    {
        $cartQuantity = intval($cartItem->cart_quantity);
        $subtotal = round(Helper::getPriceNew($cartItem) * $cartQuantity,2);
        $isStockSufficient = $cartItem->cart_quantity <= $cartItem->sku_quantity;
        $transform = [
            'cart_quantity' => $cartQuantity,
            'subtotal' => $subtotal,
            'subtotal_format' => number_format($subtotal, 2),
            'is_stock_sufficient' => $isStockSufficient,
        ];
        if (isset($cartItem->options))
            $transform['options'] = ucfirst($cartItem->options);
        return $transform;
    }
}
