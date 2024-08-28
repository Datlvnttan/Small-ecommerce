<?php

namespace Modules\Product\Transformers;

use Illuminate\Support\Facades\Storage;
use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\ProductImage;

class ProductImageTransformer extends TransformerAbstract
{
    public function transform(ProductImage $productImage)
    {
        return [
            'product_id' => $productImage->product_id,
            'image_path' => url(Storage::url(ProductImage::PATH_PRODUCT_IMAGE . $productImage->image_name)),
            'default'=>false, 
        ];
    }
}
