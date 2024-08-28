<?php

namespace Modules\Payment\Http\Controllers\View;

use App\Helpers\Call;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Http\Requests\PaypalRequest;
use Modules\Payment\Services\PayPalService;

class PaypalController extends Controller
{
    protected $paypalService;
    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function orderComplete($orderKey, PaypalRequest $request)
    {
        return Call::SafeExecute(function () use ($orderKey, $request) {
            $PayerID = $request->input('PayerID');
            $paymentId = $request->input('paymentId');
            $checkoutKey = $request->input('checkoutKey');
            $orderId = $this->paypalService->callBack($paymentId, $PayerID, $orderKey);
            return redirect()->route('web.order.checkout.order-success', [
                'orderId' => $orderId,
                'orderKey' => $orderKey,
                'checkoutKey' => $checkoutKey
            ]);
        });
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function cancel(Request $request,$orderKey)
    {

        return Call::SafeExecute(function () use ($orderKey, $request) {
            return view('order::order-paypal-cancel',[
                'orderKey' => $orderKey,
                'checkoutKey' => $request->input('checkoutKey')
            ]);
        });
        
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('payment::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('payment::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
