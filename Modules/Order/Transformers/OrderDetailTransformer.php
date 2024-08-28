<?php

namespace Modules\Order\Transformers;

use Illuminate\Support\Facades\Storage;
use League\Fractal\TransformerAbstract;
use Modules\Order\Entities\OrderDetail;
use Modules\Product\Entities\ProductImage;

class OrderDetailTransformer extends TransformerAbstract
{
    public function transform(OrderDetail $orderDetail)
    {
        $subtotal = $orderDetail->price * $orderDetail->quantity;
        return [
             'order_id'=>$orderDetail->order_id,
             'sku_id'=>$orderDetail->sku_id,
             'product_id'=>$orderDetail->sku->product->id,
             'product_name'=>$orderDetail->sku->product->product_name,
            //  'image_path'=>url(Storage::url(ProductImage::PATH_PRODUCT_IMAGE . $orderDetail->sku->product_id . "_default.jpg")),
             'image_path'=>$orderDetail->sku->product->cover_image,
             'options'=>$orderDetail->options,
             'price'=>$orderDetail->price,
             'price_format'=>number_format($orderDetail->price,2),
             'quantity'=>$orderDetail->quantity,
             'subtotal'=>$subtotal,
             'subtotal_format'=> number_format($subtotal,2),
             'feedback_created_at'=>$orderDetail->feedback_created_at,
             'feedback_incognito'=>$orderDetail->feedback_incognito,
             'feedback_image'=>$orderDetail->feedback_image,
             'feedback_path_image'=>$orderDetail->feedback_path_image,
             'feedback_rating'=>$orderDetail->feedback_rating,
             'feedback_review'=>$orderDetail->feedback_review,
             'feedback_status'=>$orderDetail->feedback_status,
             'feedback_title'=>$orderDetail->feedback_title,
        ];
    }
}
