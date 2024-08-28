<?php

namespace Modules\Product\Services;

use Modules\Product\Repositories\Repository\FlashSaleRepository;
use Modules\Product\Repositories\Repository\ProductFlashSaleRepository;

class ProductFlashSaleService
{
    protected $productFlashSaleRepository;
    public const PER_PAGE_FLASH_SALE = 10;
    public function __construct(ProductFlashSaleRepository $productFlashSaleRepository)
    {
        $this->productFlashSaleRepository = $productFlashSaleRepository;
    }

    // public function getProductFlashSales($flashSaleId,$user=null)
    // {
    //     $type = !isset($user) ? 'guest' : "member_{$user->member_type}";
    //     return $this->productFlashSaleRepository->getProductFlashSaleByFlashSaleId($flashSaleId,$type);
    // }
}
