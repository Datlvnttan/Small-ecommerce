<?php

namespace Modules\Cart\Services;

use Illuminate\Support\Facades\Session;
use Modules\Cart\Repositories\Interface\CartRepositoryInterface;
use Modules\Product\Repositories\Interface\SkuRepositoryInterface;

class CartService
{
    protected $cartRepositoryInterface;
    protected $skuRepositoryInterface;
    public const SESSION_CART = 'cart';
    public function __construct(CartRepositoryInterface $cartRepositoryInterface, SkuRepositoryInterface $skuRepositoryInterface)
    {
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->skuRepositoryInterface = $skuRepositoryInterface;
    }
    public function getCartByUserId($userId)
    {
        return $this->cartRepositoryInterface->getCartByUserId($userId);
    }
    public function getCartItems($user = null)
    {
        if (isset($user)) {
            return $this->getCartByUserId($user->id);
        } else
            return $this->getSessionCart();
    }
    public function getSessionCartBasic()
    {
        return Session::get(CartService::SESSION_CART);
    }
    public function pullSessionCartBasic()
    {
        return Session::pull(CartService::SESSION_CART);
    }
    public function getSessionCart()
    {
        $cartItems = $this->getSessionCartBasic();
        if (isset($cartItems)) {
            $skuIds = array_map(function ($obj) {
                return $obj['sku_id'];
            }, $cartItems);
            $skus = $this->skuRepositoryInterface->getBySkuIds($skuIds);
            return $this->mergeCartQuantitiesBySkuId($cartItems, $skus);
        }
        return [];
    }
    /**
     * Summary of mergeCartQuantitiesBySkuId
     * @param mixed $cartItems
     * @param mixed $skus
     * @return void
     */
    protected function mergeCartQuantitiesBySkuId($cartItems, $skus)
    {
        $skuCollection = collect($skus);
        $cartCollection = collect($cartItems);

        $skuCollection->map(function ($item) use ($cartCollection) {
            $quantityItem = $cartCollection->firstWhere('sku_id', $item->sku_id);
            // $item->cart_quantity = $quantityItem['quantity'];
            if ($quantityItem) {
                $item->cart_quantity = $quantityItem['quantity'];
            } else {
                $item->cart_quantity = null;
            }
            return $item;
        });
        return $skuCollection;
    }
    public function addProductToCart($user,$productId,$skuId,$quantity)
    {
        $sku = $this->skuRepositoryInterface->findSkuBySkuIdOrSkuDefaultByProductId($productId, $skuId);
        return isset($user) ? $this->addProductToDBCart($user->id, $sku, $quantity) : $this->addProductToSessionCart( $sku, $quantity);
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
    public function addProductToDBCart($userId, $sku, $quantity = 1)
    {
        // if (isset($skuId))
        //     $sku = $this->skuRepositoryInterface->findSkuDefaultByProductId($productId)->id;
        // else
        //     $sku = $this->skuRepositoryInterface->find($skuId);
        $skuId = $sku->id;
        $cartItem = $this->cartRepositoryInterface->find([
            'user_id' => $userId,
            'sku_id' => $skuId,
        ]);
        if (isset($cartItem)) {
            if ($cartItem->quantity + $quantity > $sku->quantity)
                return false;
            $cartItem->quantity += $quantity;
            $cartItem->save();
            return true;
        } else {
            if ($quantity > $sku->quantity)
                return false;
            $cartItem = $this->cartRepositoryInterface->create([
                "user_id" => $userId,
                "sku_id" => $skuId,
                "quantity" => $quantity,
            ]);
            return true;
        }
    }
    /**
     * Summary of pushDataSessionCart
     * @param mixed $cartItems
     * @return void
     */
    public function pushDataSessionCart($cartItems)
    {
        Session::put(config('app.CART'), $cartItems);
    }
    /**
     * Summary of addProductToSessionCart
     * @param mixed $productId
     * @param mixed $skuId
     * @param mixed $quantity
     * @return bool
     */
    public function addProductToSessionCart($sku, $quantity = 1)
    {
        $cartItems = $this->getSessionCartBasic();
        if (isset($cartItems)) {
            foreach ($cartItems as &$cartItem) {
                if ($cartItem["sku_id"] == $sku->id) {
                    if ($cartItem['quantity'] + $quantity > $sku->quantity)
                        return false;
                    $cartItem["quantity"] += $quantity;
                    $this->pushDataSessionCart($cartItems);
                    return true;
                }
            }
        } else
            $cartItems = [];
        if ($quantity > $sku->quantity)
            return false;
        array_push($cartItems, [
            'sku_id' => $sku->id,
            'quantity' => $quantity
        ]);
        // return 2;
        $this->pushDataSessionCart($cartItems);
        return true;
    }
    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     * @param mixed $user
     * @param mixed $skuId
     * @param mixed $quantity
     * @return bool
     */
    public function updateQuantityProductInTheCart($user, $skuId, $quantity)
    {
        $sku = $this->skuRepositoryInterface->find($skuId);
        if (isset($user))
            return $this->updateQuantityProductInDBCart($user->id, $sku, $quantity);
        return $this->updateQuantityProductInSessionCart($sku, $quantity);
    }
    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng session
     * @param mixed $skuId
     * @param mixed $quantity
     * @return bool
     */
    protected function updateQuantityProductInSessionCart($sku, $quantity)
    {
        $cartItems = $this->getSessionCartBasic();
        // return $cartItems;
        if (isset($cartItems)) {
            foreach ($cartItems as &$cartItem) {
                if ($cartItem["sku_id"] == $sku->id) {
                    if ($quantity > $sku->quantity)
                        return false;
                    $cartItem["quantity"] = $quantity;
                    $this->pushDataSessionCart($cartItems);
                    return true;
                }
            }
        }
        throw new \Exception('Product not found in cart');
    }
    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng DB
     * @param mixed $userId
     * @param mixed $skuId
     * @param mixed $quantity
     * @return bool
     */
    protected function updateQuantityProductInDBCart($userId, $sku, $quantity)
    {
        $cartItem = $this->cartRepositoryInterface->find([
            'user_id' => $userId,
            'sku_id' => $sku->id,
        ]);
        if (isset($cartItem)) {
            if ($quantity <= 0) {
                $cartItem->delete();
                return true;
            }
            if ($quantity > $sku->quantity)
                return false;
            $cartItem->quantity = $quantity;
            $cartItem->save();
            return true;
        }
        throw new \Exception('Product not found in cart');
        // return $this->cartRepositoryInterface->update([$userId,$skuId],['quantity' => $quantity]);
    }
    /**
     * Xóa sản phẩm khỏi giỏ hàng
     * @param mixed $skuId
     * @param mixed $user
     * @return void
     */
    public function removeProductFromCart($skuId, $user)
    {
        if (isset($user))
            return $this->removeProductFromDBCart($user->id, $skuId);
        return $this->removeProductFromSessionCart($skuId);
    }
    /**
     * Xóa sản phẩm khỏi giỏ hàng ở DB
     * @param mixed $userId
     * @param mixed $skuId
     * @return bool
     */
    protected function removeProductFromDBCart($userId, $skuId)
    {
        $cartItem = $this->cartRepositoryInterface->find([
            'user_id' => $userId,
            'sku_id' => $skuId,
        ]);
        if (isset($cartItem)) {
            $cartItem->delete();
            return true;
        }
        return false;
        // return $this->cartRepositoryInterface->delete([$userId,$skuId]);
    }
    /**
     * 
     * Xóa sản phẩm khỏi giỏ hàng session
     * @param mixed $skuId
     * @return void
     */
    protected function removeProductFromSessionCart($skuId)
    {
        $cartItems = $this->getSessionCartBasic();
        if (isset($cartItems)) {
            foreach ($cartItems as $key => $cartItem) {
                if ($cartItem["sku_id"] == $skuId) {
                    unset($cartItems[$key]);
                    $this->pushDataSessionCart($cartItems);
                    return true;
                }
            }
        }
        return false;
    }
    public function cleanItemsByOrderDetails($orderDetails, $userId = null)
    {
        $skuIds = collect($orderDetails)->pluck('sku_id')->all();
        // $skuIds = array_map(function ($orderDetail) {
        //     return $orderDetail->sku_id;
        // }, $orderDetails);
        if (isset($userId)) {
            $this->cartRepositoryInterface->removeItemsBykuIds($skuIds, $userId);
        } else {
            $this->removeItemFormSessionCartBySkuIds($skuIds);
        }
    }
    protected function removeItemFormSessionCartBySkuIds($skuIds)
    {
        $cartItems = $this->getSessionCartBasic();
        if (isset($cartItems)) {
            foreach ($skuIds as $skuId) {
                foreach ($cartItems as $key => $cartItem) {
                    if ($cartItem["sku_id"] == $skuId) {
                        unset($cartItems[$key]);
                        // $this->pushDataSessionCart($cartItems);
                        // break;
                    }
                }
            }
            $this->pushDataSessionCart($cartItems);
        }
    }
    public function syncSessionCartWithDatabase(int $userId)
    {
        $sessionCartItems = $this->pullSessionCartBasic();
        if(isset($sessionCartItems))
        {
            foreach ($sessionCartItems as $cartItem) {
                $sku = $this->skuRepositoryInterface->find($cartItem['sku_id']);
                $this->addProductToDBCart($userId, $sku, $cartItem['quantity']);
            }
        }
    }
}
