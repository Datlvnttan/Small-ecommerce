<?php

namespace Modules\Product\Transformers;

use App\Helpers\Helper;
use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Sku;

class SkuTransformer extends BaseProductTransformer
{
    public function additionalTransform($sku)
    {
        return [
            // 'sku_id' => $sku->sku_id,
            // 'product_id' => $sku->id,
            // 'product_part_number' => $sku->product_part_number,
            // 'price_old' => $sku->price,
            // 'sku_quantity' => $sku->sku_quantity,
            // 'discount_percent' => intval($sku->discount*100),
            // 'product_discount_percent' => intval($sku->product_discount * 100),
            // 'price_new' => Helper::calculatePrice($sku),
        ];
    }
}
