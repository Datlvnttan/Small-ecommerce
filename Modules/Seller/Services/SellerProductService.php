<?php

namespace Modules\Seller\Services;

use Modules\Seller\Repositories\Interface\SellerProductRepositoryInterface;

class SellerProductService
{
    public const PER_PAGE = 10;
    protected $sellerProductRepository;
    public function __construct(SellerProductRepositoryInterface $sellerProductRepository)
    {
        $this->sellerProductRepository = $sellerProductRepository;
    }
    public function getAllProductsBySellerId($sellerId)
    {
        return $this->sellerProductRepository->getAllProductsBySellerId($sellerId);
    }
   
}
