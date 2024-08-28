<?php

namespace Modules\Product\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Product;
use Modules\Product\Services\ProductService;

// use Modules\Product\Entities\Product;

class ItemProductTransformer extends BaseProductTransformer
{
    public function additionalTransform($itemProduct)
    {
        return [
        ];
    }
}
