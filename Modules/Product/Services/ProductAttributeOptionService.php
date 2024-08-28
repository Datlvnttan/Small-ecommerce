<?php

namespace Modules\Product\Services;

use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Repositories\Interface\ProductAttributeOptionRepositoryInterface;

class ProductAttributeOptionService
{
    protected $productAttributeOptionRepository;
    public function __construct(ProductAttributeOptionRepositoryInterface $productAttributeOptionRepository)
    {
        $this->productAttributeOptionRepository = $productAttributeOptionRepository;
    }
    public function getProductAttributeOptionByProductId($productId)
    {
        return $this->productAttributeOptionRepository->getProductAttributeOptionByProductId($productId);
    }

   
}
