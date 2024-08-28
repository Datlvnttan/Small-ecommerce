<?php

namespace Modules\Product\Transformers;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductImage;

abstract class BaseProductTransformer extends TransformerAbstract
{
    protected $discountNew;
    protected $priceNew;
    protected $isFlashSale;
    public function transform(Model $baseProduct)
    {
        $is_flash_sale = Helper::getIsFlashSale($baseProduct);
        $discountNew = Helper::getDiscountNew($baseProduct);

        $priceNew = Helper::getPriceNew($baseProduct);
        $baseTransform = [
            'product_id' => $baseProduct->id,
            'sku_id' => $baseProduct->sku_id,
            // 'image_path' => url(Storage::url(ProductImage::PATH_PRODUCT_IMAGE . $baseProduct->id . "_default.jpg")),
            'image_path' => $baseProduct->cover_image,
            'product_name' => ucfirst($baseProduct->product_name),
            'shipping_point' => $baseProduct->shipping_point,
            'category_id' => $baseProduct->category_id,
            'brand_id' => $baseProduct->brand_id,
            'average_rating' => round($baseProduct->average_rating, 2),
            'total_rating' => $baseProduct->total_rating,
            'created_at' => $baseProduct->created_at,
            'price_old' => $baseProduct->price,
            'discount' => $discountNew,
            'price_old_format' => number_format($baseProduct->price, 2),
            'discount_percent' => intval($discountNew * 100),
            'product_discount_percent' => intval($baseProduct->product_discount * 100),
            'is_flash_sale' => $is_flash_sale,
            'product_flash_sale_discount' => $baseProduct->product_flash_sale_discount,
            'product_flash_sale_discount_percent' => intval($baseProduct->product_flash_sale_discount * 100),
            'product_flash_sale_end_time' => $baseProduct->product_flash_sale_end_time,
            'product_flash_sale_start_time' => $baseProduct->product_flash_sale_start_time,
            'price_new' => $priceNew,
            'price_new_format' => number_format($priceNew, 2),
            'sku_quantity' => $baseProduct->sku_quantity,
            'total_quantity_sold' => $baseProduct->total_quantity_sold,
            'is_hot' => Product::isHot($baseProduct->total_quantity_sold),
            'is_new' => Product::isNew($baseProduct->created_at)

        ];
        return array_merge($baseTransform, $this->additionalTransform($baseProduct));
    }
    abstract protected function additionalTransform($baseProduct);
}
