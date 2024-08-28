<?php

namespace Modules\Product\Repositories\Interface;

use App\Repositories\RepositoryInterface;

interface SkuRepositoryInterface extends RepositoryInterface
{
    public function checkSkuQuantity($skuId, $quantity);
    public function findSkuDefaultByProductId($productId);
    public function findSkuBySkuIdOrSkuDefaultByProductId($productId, $skuId);
    public function getByOptions($productId, array $optionIds);
    public function getByProductPartNumber($productPartNumber, $memberType = "guest");

    public function getBySkuIds($skuIds,$memberType = "guest");

    public function updateInventoryFromOrderItems(int $orderId,bool $add = true);
}
