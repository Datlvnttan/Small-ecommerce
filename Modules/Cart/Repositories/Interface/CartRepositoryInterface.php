<?php
namespace Modules\Cart\Repositories\Interface;

use App\Repositories\RepositoryInterface;

interface CartRepositoryInterface extends RepositoryInterface
{
    public function getCartByUserId($userId);
    public function addProductToCart($userId,$skuId,$quantity = 1);
    public function removeItemsBykuIds($skuIds,$userId);
}
