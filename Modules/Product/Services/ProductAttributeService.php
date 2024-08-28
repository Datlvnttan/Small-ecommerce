<?php

namespace Modules\Product\Services;

use Modules\Product\Repositories\Interface\ProductAttributeRepositoryInterface;

class ProductAttributeService
{
    protected $productAttributeRepository;
    public function __construct(ProductAttributeRepositoryInterface $productAttributeRepository)
    {
        $this->productAttributeRepository = $productAttributeRepository;
    }

   
}
