<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Resource\Item;
use Modules\Cart\Services\CartService;
use Modules\Order\Http\Requests\CancelOrderGuestRequest;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Http\Requests\RetryOrderPaymentPayPalRequest;
use Modules\Order\Services\OrderService;
use Modules\Order\Transformers\CheckoutTransformer;
use Modules\Order\Transformers\OrderTransformer;

class OrderApiController extends Controller
{
    protected $orderService;
    protected $cartService;
    public function __construct(OrderService $orderService, CartService $cartService)
    {
        $this->orderService = $orderService;
        $this->cartService = $cartService;
    }
    public function buildDataOrder(Request $request)
    {
        return Call::TryCatchResponseJson(function () use ($request) {
            $shippingMethodId = $request->shippingMethodId;
            if (isset($shippingMethodId)) {
                $user = Auth::user();
                $cartItems = $this->cartService->getCartItems($user);
                if (count($cartItems) > 0) {
                    $url = $this->orderService->buildDataOrder($cartItems, $shippingMethodId);
                    if ($url !== false) {
                        return ResponseJson::success(data: $url);
                    }
                }
                return ResponseJson::failed('Cart is empty');
            }
            return ResponseJson::failed('Shipping method is required');
        });
    }
    public function checkout($key)
    {
        return Call::TryCatchResponseJsonFractalManager(function ($fractal) use ($key) {
            $order = $this->orderService->getDataOrder($key);
            if (isset($order)) {
                $resource = new Item($order, new CheckoutTransformer());
                $checkoutData = $fractal->createData($resource)->toArray();
                return ResponseJson::success(data: $checkoutData['data']);
            }
            return ResponseJson::failed('Checkout has expired or does not exist');
        });
    }
    public function createDataOrder($key, OrderRequest $request)
    {
        return Call::TryCatchResponseJson(function () use ($key, $request) {
            $dataOrderCheckout = $this->orderService->getDataOrder($key);
            if (isset($dataOrderCheckout)) {
                $orderDetails = $dataOrderCheckout['orderDetails'];
                if (isset($orderDetails)) {
                    $user = Auth::user();
                    $dataResponse = $this->orderService->submitDataOrder($orderDetails, $request->all(), $user, $key);
                    return ResponseJson::success(data: $dataResponse);
                }
            }
            return ResponseJson::failed('Checkout has expired');
        });
    }
    public function retryOrderPaymentPaypal($checkoutKey, RetryOrderPaymentPayPalRequest $request)
    {
        return Call::TryCatchResponseJson(function () use ($request, $checkoutKey) {
            $orderKey = $request->orderKey;
            $dataOrderCheckout = $this->orderService->getDataOrder($checkoutKey);
            if (isset($dataOrderCheckout)) {
                if (isset($orderKey)) {
                    $dataOrder = $this->orderService->getDataOrderPaymentPaypal($orderKey);
                    if (isset($dataOrder)) {
                        $paymentUrl = $this->orderService->retryOrderPayment($checkoutKey, $dataOrder);
                        return ResponseJson::success(data: $paymentUrl);
                    }
                    return ResponseJson::failed('Order not found');
                }
                return ResponseJson::failed('Order key does not exist');
            }
            return ResponseJson::failed('Checkout has expired or does not exist');
        });
    }

    public function cancelOrder($orderId, OrderRequest $request)
    {
        return Call::TryCatchResponseJson(function () use ($orderId, $request) {
            $reason = $request->input('reason');
            $status = $this->orderService->cancelOrder($orderId, $reason);
            switch ($status) {
                case 1:
                    return ResponseJson::success('Cancel order successfully');
                case 2:
                    return ResponseJson::success('An OTP code has been sent to the email you placed your order, please enter the OTP code to confirm order cancellation', [
                        'url' => route('web.order.cancelEnterOTP', [
                            'orderId' => $orderId
                        ])
                    ]);
                default:
                    return ResponseJson::error('Failed, please try again later');
            }
        });
    }
    public function cancelOrderGuestEnterOTP($orderId, CancelOrderGuestRequest $request)
    {
        return Call::TryCatchResponseJson(function () use ($orderId, $request) {
            $otp = $request->input('otp');
            $success =  $this->orderService->verifyCancelOrderGuest($orderId, $otp);
            if ($success) {
                return ResponseJson::success('Cancel order successfully', [
                    'url' => route('web.order.track-order-details', [
                        'orderId' => $orderId
                    ])
                ]);
            }
        });
    }

    // public function findTrackOrder(OrderRequest $request)
    // {
    //     return Call::TryCatchResponseJson(function () use ($request) {
    //         $orderKey = $request->orderKey;
    //         $order = $this->orderService->findOrderDetailsByOrderKey($orderKey);
    //         if (isset($order)) {
    //             return ResponseJson::success(data: $order);
    //         }
    //         return ResponseJson::failed('Order not found');
    //     });
    // }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return Call::TryCatchResponseJsonFractalManager(function ($fractal) use ($id) {
            // $user = Auth::user();
            $order = $this->orderService->getOrderDetailsByOrderId($id);
            $order->setMasked(true);

            if (isset($order)) {
                $resource = new Item($order, new OrderTransformer());
                $orderTransformer = $fractal->createData($resource)->toArray();
                return ResponseJson::success(data: $orderTransformer['data']);
            }
            return ResponseJson::error('Order not found');
        });
    }
    public function resendEmailCancelOrderGuest($orderId)
    {
        return Call::TryCatchResponseJson(function () use ($orderId) {
            $this->orderService->resendEmailCancelOrderGuest($orderId);
            return ResponseJson::success('successfully');
        });
    }
}
