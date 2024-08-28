<?php

namespace Modules\Product\Services;

use Modules\Product\Repositories\Interface\FlashSaleRepositoryInterface;
use Modules\Product\Repositories\Repository\FlashSaleRepository;

class FlashSaleService
{
    protected $flashSaleRepositoryInterface;
    public function __construct(FlashSaleRepositoryInterface $flashSaleRepositoryInterface)
    {
        $this->flashSaleRepositoryInterface = $flashSaleRepositoryInterface;
    }

    public function all()
    {
        return $this->flashSaleRepositoryInterface->all();
    }
    public function getFitFlashSale()
    {
        return $this->flashSaleRepositoryInterface->getFitFlashSale();
    }
}
