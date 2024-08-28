<?php

namespace Modules\Order\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Modules\Order\Entities\Order;

class OrderTransformer extends TransformerAbstract
{
    public function transform(Order $order)
    {
        $totalAmount = round($order->total_amount,2);
        $totalAmountFormat = number_format($totalAmount,2);
        return [
            'id' => $order->id,
            'billing_address' => $order->billing_address,
            'created_at' => $order->created_at,
            'current_status' => $order->current_status,
            'delivery_address' => $order->delivery_address, 
            'discount_coupon' => $order->discount_coupon, 
            'email' => $order->email, 
            'is_allowed_cancel' => $order->is_allowed_cancel, 
            'is_evaluate' => $order->is_evaluate, 
            'is_paid' => $order->is_paid, 
            'note' => $order->note, 
            'order_details' => $this->includeOrderDetails($order)['data'], 
            'order_key' => $order->order_key, 
            'payment_method' => $order->payment_method, 
            'shipping_method' => $order->shipping_method, 
            'status' => $order->status, 
            'total_amount' => $totalAmount,
            'total_amount_format' => $totalAmountFormat, 
            'total_point' => $order->total_point, 
            'user_id' => $order->user_id, 
        ];
    }
    public function includeOrderDetails($orderHistory)
    {
        $fractal = new Manager();
        $result = $this->collection($orderHistory->orderDetails, new OrderDetailTransformer());
        return $fractal->createData($result)->toArray();
    }
}
