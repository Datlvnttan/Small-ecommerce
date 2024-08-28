<?php

namespace Modules\Product\Transformers;

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductImage;

class ItemProductElasticSearchTransformer extends TransformerAbstract
{
    protected $memberType;
    public function __construct($memberType)
    {
        $this->memberType = $memberType;
    }
    public function transform($itemProductElasticSearch)
    {
        // $product = $itemProductElasticSearch['product_detail']['hits']['hits'][0]['_source'];
        // $skuDefault = $itemProductElasticSearch['sku_product_nested']['sku_default']['sku_detail']['hits']['hits'][0]['_source'];

        // $flashSaleDiscount = $itemProductElasticSearch['filtered_flash_sale']['field_flash_sale_discount']['value'] ?? 0;
        // $isFlashSale = $flashSaleDiscount > 0;
        // $discountNew = $itemProductElasticSearch['discounted']['value'];
        // $priceNew = $itemProductElasticSearch['discounted_price']['value'];
        // $startFlashSale = null;
        // $endFlashSale = null;
        // if (isset($product['product_flash_sale_active'])) {
        //     $startFlashSale = Carbon::parse($product['product_flash_sale_active']['start_time']);
        //     $endFlashSale = Carbon::parse($product['product_flash_sale_active']['end_time']);
        // }

        // $priceNew = $itemProductElasticSearch['discounted'];


        $isFlashSale = false;
        $product =  $itemProductElasticSearch['_source'];
        $skuDefault =  $itemProductElasticSearch['inner_hits']['skus']['hits']['hits'][0]['_source'];
        $discountNew = $skuDefault["{$this->memberType}_discount"];
        $flashSaleDiscount = null;
        $startFlashSale = $endFlashSale = $productFlashSaleDiscountPercent = null;
        if (
            isset($product['product_flash_sale_active']) &&
            isset($product['product_flash_sale_active']['start_time']) &&
            isset($product['product_flash_sale_active']['end_time'])
        ) {
            $startFlashSale = Carbon::parse($product['product_flash_sale_active']['start_time']);
            $endFlashSale = Carbon::parse($product['product_flash_sale_active']['end_time']);
            $currentTime = now();
            if ($currentTime >= $startFlashSale && $currentTime <= $endFlashSale) {
                $isFlashSale = true;
                $flashSaleDiscount = $product['product_flash_sale_active']['discount'];
                $discountNew = min($discountNew + $flashSaleDiscount, 1);
                $productFlashSaleDiscountPercent = intval($flashSaleDiscount * 100);
            }
        }
        $priceNew = $skuDefault["{$this->memberType}_price"] * (1 - $discountNew);
        $itemProduct = [
            'product_id' => $product['id'],
            'sku_id' => $skuDefault['id'],
            // 'image_path' => url(Storage::url(ProductImage::PATH_PRODUCT_IMAGE . $itemProductElasticSearch['id'] . "_default.jpg")),
            'image_path' => $product['cover_image'],
            'product_name' => ucfirst($product['product_name']),
            'shipping_point' => $product['shipping_point'],
            'category_id' => $product['category_id'],
            'brand_id' => $product['brand_id'],
            'average_rating' => round($product['average_rating'], 2),
            'total_rating' => $product['total_rating'],
            'created_at' => $product['created_at'],
            'price_old' => $skuDefault["{$this->memberType}_price"],
            'discount' => $discountNew,
            'price_old_format' => number_format($skuDefault["{$this->memberType}_price"], 2),
            'discount_percent' => intval($discountNew * 100),
            'is_flash_sale' => $isFlashSale,
            'product_flash_sale_discount' => $flashSaleDiscount,
            'product_flash_sale_discount_percent' => $productFlashSaleDiscountPercent,
            'product_flash_sale_end_time' => $endFlashSale,
            'product_flash_sale_start_time' => $startFlashSale,
            'price_new' => $priceNew,
            'price_new_format' => number_format($priceNew, 2),
            'total_quantity_sold' => $product['total_quantity_sold'],
            'is_hot' => Product::isHot($product['total_quantity_sold']),
            'is_new' => Product::isNew($product['created_at']),
            'sellers' => $product['sellers'] ?? null,
            'specifications' => $product['specifications'] ?? null,

        ];
        return $itemProduct;
    }
}
