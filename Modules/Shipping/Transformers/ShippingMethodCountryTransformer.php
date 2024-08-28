<?php

namespace Modules\Shipping\Transformers;

use League\Fractal\TransformerAbstract;


class ShippingMethodCountryTransformer extends TransformerAbstract
{
    public function transform($shippingMethod)
    {
        $discount_amount = $shippingMethod->shipping_method_country_expense * $shippingMethod->shipping_method_country_discount;
        $expensiveNew = round($shippingMethod->shipping_method_country_expense - $discount_amount,2);
        return [
            'shipping_method_id' => $shippingMethod->id,
            'shipping_method_name' => ucfirst($shippingMethod->shipping_method_name),
            'shipping_method_country_expense_old' => $shippingMethod->shipping_method_country_expense,
            'shipping_method_country_expense_old_format' => number_format($shippingMethod->shipping_method_country_expense,2),
            'shipping_method_country_discount_percent' => $shippingMethod->shipping_method_country_discount*100,
            'shipping_method_country_expense_new' => $expensiveNew,
            'shipping_method_country_expense_new_format' => number_format($expensiveNew,2),
            'shipping_method_country_delivery_time' => $shippingMethod->shipping_method_country_delivery_time,
            'discount_amount' => $discount_amount,
            'discount_amount_format' => number_format($discount_amount,2),
            'default' => $shippingMethod->default,
        ];
    }
}
