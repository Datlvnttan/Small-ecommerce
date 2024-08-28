<?php

namespace Modules\Product\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Modules\Product\Repositories\Interface\ProductImageRepositoryInterface;

class ProductImageRepository extends EloquentRepository implements ProductImageRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Product\Entities\ProductImage::class;
    }
    public function allByProductId($productId)
    {
        return $this->model->where('product_id', $productId)->get();
    }
}
