<?php

namespace Modules\Cart\Services;

use Illuminate\Support\Facades\Session;
use Modules\Cart\Repositories\Interface\FavoriteRepositoryInterface;
use Modules\Product\Repositories\Repository\ProductRepository;
use Modules\Product\Repositories\Repository\SkuRepository;

class FavoriteService
{
    protected $favoriteRepositoryInterface;
    protected $skuRepository;
    public function __construct(FavoriteRepositoryInterface $favoriteRepositoryInterface)
    {
        $this->favoriteRepositoryInterface = $favoriteRepositoryInterface;
    }
    public function getProductFavoriteByUserId($userId,$member_type)
    {
        return $this->favoriteRepositoryInterface->getProductFavoriteByUserId($userId,$member_type);
    }
    public function getSessionFavorite()
    {
        return Session::get(config('app.FAVORITE'));
    }
    /**
     * Thêm sản phẩm vào giỏ hàng ở dưới database
     *
     * @param [type] $userId
     * @param [type] $productId
     * @param [type] $skuId
     * @param integer $quantity
     * @return boolean
     */
    public function addProductToDBFavorite($userId, $productId)
    {
        $favoriteItem = $this->favoriteRepositoryInterface->find([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
        if (isset($favoriteItem)) {
            return false;
        } else {
            $favoriteItem = $this->favoriteRepositoryInterface->create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            return true;
        }
    }
    /**
     * Summary of pushDataSessionCart
     * @param mixed $favoriteItems
     * @return void
     */
    public function pushDataSessionFavorite($favoriteItems)
    {
        Session::put(config('app.FAVORITE'), $favoriteItems);
    }
    /**
     * Summary of addProductToSessionCart
     * @param mixed $productId
     * @param mixed $skuId
     * @param mixed $quantity
     * @return bool
     */
    public function addProductToSessionFavorite($productId)
    {
        $favoriteItems = $this->getSessionFavorite();
        if (isset($favoriteItems)) {
            foreach ($favoriteItems as $favoriteItem) 
                if ($favoriteItem["product_id"] == $productId) 
                    return false;
        } else
            $favoriteItems = [];
        array_push($favoriteItems, [
            'product_id' => $productId,
        ]);
        $this->pushDataSessionFavorite($favoriteItems);
        return true;
    }
}
