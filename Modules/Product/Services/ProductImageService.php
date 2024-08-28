<?php

namespace Modules\Product\Services;

use Modules\Product\Repositories\Interface\ProductImageRepositoryInterface;

class ProductImageService
{
    protected $productImageRepository;
    public function __construct(ProductImageRepositoryInterface $productImageRepository)
    {
        $this->productImageRepository = $productImageRepository;
    }

    public function allByProductId($productId)
    {
        return $this->productImageRepository->allByProductId($productId);
    }
   
}
