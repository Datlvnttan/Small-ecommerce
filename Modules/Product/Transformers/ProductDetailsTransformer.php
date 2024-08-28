<?php

namespace Modules\Product\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\Sku;

class ProductDetailsTransformer extends BaseProductTransformer
{
    public function additionalTransform($productDetails)
    {

        // $this->handleIfThereIsFlashSale($productDetails);
        return [
            'describe' => ucfirst($productDetails->describe),
            'detail' => ucfirst($productDetails->detail),
            'options_default' => Sku::getOptions($productDetails->product_part_number),
            'product_attributes' => $productDetails->productAttributes,
            'product_images' => $this->includeProductImages($productDetails)['data'], //->productImages,
            'product_flash_sale_active' => $productDetails->productFlashSaleActive,

        ];
    }
    public function includeProductImages(Product $product)
    {
        $fractal = new Manager();
        $images = $product->productImages;
        $result = $this->collection($images, new ProductImageTransformer());
        $data = $fractal->createData($result)->toArray();
        if(!isset($data))
        {
            $data = [];
        }
        array_push($data['data'],[
            'product_id'=>$product->id,
            'image_path'=>$product->cover_image,
            'default'=>true
        ]);
        return $data;
    }
}
