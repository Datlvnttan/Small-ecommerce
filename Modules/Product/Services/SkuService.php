<?php

namespace Modules\Product\Services;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Log;
use Modules\Product\Repositories\Interface\SkuRepositoryInterface;
use Modules\User\Entities\User;

class SkuService
{
    protected $skuRepositoryInterface;
    public function __construct(SkuRepositoryInterface $skuRepositoryInterface)
    {
        $this->skuRepositoryInterface = $skuRepositoryInterface;
    }
    public function getByOptions(array $optionIds, User $user = null)
    {
        sort($optionIds);
        $productPartNumber = implode('-', $optionIds);
        $memberType = Helper::getMemberType($user);
        return $this->skuRepositoryInterface->getByProductPartNumber($productPartNumber, $memberType);
    }
    public function updateInventoryFromOrderItems($orderId, bool $add = true)
    {
        // Log::info('ựehweg');
        // Log::info($orderId);
        return $this->skuRepositoryInterface->updateInventoryFromOrderItems($orderId, $add);
    }
}
