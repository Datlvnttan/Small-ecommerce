<?php
namespace Modules\Seller\Repositories\Interface;

use App\Repositories\RepositoryInterface;

interface SellerProductRepositoryInterface extends RepositoryInterface
{
    public function getAllProductsBySellerId(int $sellerId);
}
