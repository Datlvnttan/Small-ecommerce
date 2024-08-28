<?php

namespace Modules\Cart\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use League\Fractal\Resource\Collection;
use Modules\Cart\Http\Requests\CartRequest;
use Modules\Cart\Services\CartService;
use Modules\Cart\Transformers\CartTransformer;
use Symfony\Component\Console\Input\Input;

class CartApiController extends Controller
{
    protected $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return Call::TryCatchResponseJsonFractalManager(function ($fractal) {
            $user = Auth::user();
            $cartItems = $this->cartService->getCartItems($user);
            // return $cartItems[0];
            $resource = new Collection($cartItems, new CartTransformer());
            $cartItemsTransformer = $fractal->createData($resource)->toArray();

            return ResponseJson::success(data: $cartItemsTransformer['data']);
        });
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CartRequest $request)
    {
        return Call::TryCatchResponseJson(function () use ($request) {
            $productId = $request->productId;
            $skuId = $request->skuId;
            $quantity = $request->input('quantity', 1);
            $user = Auth::user();
            $success = $this->cartService->addProductToCart($user, $productId, $skuId, $quantity);
            if ($success)
                return ResponseJson::success('Added product to cart successfully');
            return ResponseJson::failed('The quantity of products in stock is not enough');
        });
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $skuId)
    {
        return Call::TryCatchResponseJson(function () use ($request, $skuId) {
            $quantity = $request->input('quantity', 1);
            $user = Auth::user();
            $success = $this->cartService->updateQuantityProductInTheCart($user, $skuId, $quantity);
            if ($success)
                return ResponseJson::success('Updated product quantity in cart successfully');
            return ResponseJson::failed('The quantity of products in stock is not enough');
        });
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($skuId)
    {
        return Call::TryCatchResponseJson(function () use ($skuId) {
            $user = Auth::user();
            $success = $this->cartService->removeProductFromCart($skuId, $user);
            if ($success)
                return ResponseJson::success('Removed product from cart successfully');
            return ResponseJson::failed('Product not found in cart');
        });
    }
}
