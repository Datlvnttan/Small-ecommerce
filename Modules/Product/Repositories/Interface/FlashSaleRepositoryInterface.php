<?php
namespace Modules\Product\Repositories\Interface;

use App\Repositories\RepositoryInterface;

interface FlashSaleRepositoryInterface extends RepositoryInterface
{
    public function getFitFlashSale();
}
