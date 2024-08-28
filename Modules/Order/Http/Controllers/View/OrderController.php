<?php

namespace Modules\Order\Http\Controllers\View;

use App\Helpers\Call;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Services\OrderService;

class OrderController extends Controller
{
    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function checkout($key)
    {
        return view('order::checkout');
    }
    public function orderSuccess($orderId, $orderKey, Request $request)
    {
        return Call::SafeExecute(function () use ($orderId, $orderKey, $request) {
            $key = $request->checkoutKey;
            $this->orderService->cleanDataOrder($key);
            return view('order::order-success', [
                'orderId' => $orderId,
                'orderKey' => $orderKey
            ]);
        });
    }

    public function trackOrder()
    {
        return view('order::track-order');
    }

    public function verifyOrder($orderKey, Request $request)
    {
        return Call::SafeExecute(function () use ($orderKey, $request) {
            $requestTokenOrderVerify = $request->tokenOrderVerify;
            $success = $this->orderService->verifyOrder($orderKey, $requestTokenOrderVerify);
            if ($success) {
                return view('order::verify-order-success')->with([
                    'orderKey' => $orderKey
                ]);
            }
            // return redirect()->route('web.order.verify-order.success', ['orderId' => $orderKey]);
        }, 'Order validation failed!!!');
    }

    public function findTrackOrder(Request $request)
    {
        return Call::SafeExecute(function () use ($request) {
            // dd($request->all());
            $orderKey = $request->orderKey;

            $order = $this->orderService->findByOrderKey($orderKey);
            if ($order) {
                return redirect()->route('web.order.track-order-details', [
                    'orderId' => $order->id,
                ]);
            }
        });
    }
    public function orderDetails($orderId)
    {
        return Call::SafeExecute(function () use ($orderId) {
            return view('order::track-order-details', [
                'orderId' => $orderId,
            ]);
        });
    }
    public function cancelEnterOTP($orderId)
    {
        return Call::SafeExecute(function () use ($orderId) {
            $order = $this->orderService->findById($orderId);
            // dd($order->is_allowed_cancel);
            if (isset($order)) {
                if ($order->is_allowed_cancel) {
                    return view('order::order-enter-otp', [
                        'title' => 'Confirm cancel order',
                        'content' => "An OTP code has been sent to the email '{$order->email}', please enter the OTP code to confirm order cancellation",
                        'jsName' => 'confirm-cancel-order-guest',
                        'data' => [
                            'routeResendEmail' => route('api.order.cancel.resendEmailCancelOrderGuest', [
                                'orderId' => $orderId,
                            ]),
                            'routeSubmit' => route('api.order.cancelOrderGuestEnterOTP', [
                                'orderId' => $orderId,
                            ]),

                        ]
                    ]);
                }
                else
                {
                    throw new \Exception('This order is not allowed to be canceled', 403);
                }
            }
            throw new \Exception('Order does not exist', 404);
        });
    }
}
