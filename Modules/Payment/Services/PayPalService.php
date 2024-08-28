<?php

namespace Modules\Payment\Services;

use Modules\Order\Enums\OrderStatus;
use Modules\Order\Services\OrderService;
use Modules\Shipping\Repositories\Interface\CountryRepositoryInterface;
use Omnipay\Omnipay;

class PayPalService
{
    private $gateway;
    private $orderService;
    public function __construct(OrderService $orderService = null)
    {
        $this->orderService = $orderService;
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(config('paypal.client_id'));
        $this->gateway->setSecret(config('paypal.client_secret'));
        $this->gateway->setTestMode(config('paypal.mode') === 'sandbox');
    }
    public function getUrlPaymentPayPal($orderKey, $amount,$checkoutKey)
    {
        $response = $this->gateway->purchase([
            'amount' => $amount,
            'currency' => 'USD',
            'transactionId' => $orderKey,
            'description'   => 'Pay for the order',
            'returnUrl' => route('web.payment.paypal.complete', [
                'orderKey' => $orderKey,
                'checkoutKey' => $checkoutKey,
            ]),
            'cancelUrl' => route('web.payment.paypal.order-cancel',[
                'orderKey' => $orderKey,
                'checkoutKey' => $checkoutKey,
            ]),
        ])->send();
        if ($response->isRedirect()) {
            return $response->getRedirectUrl();
        } else {
            throw new \Exception($response->getMessage());
        }
    }
    public function callBack($paymentId, $PlayerID, $orderKey)
    {
        $transaction = $this->gateway->completePurchase([
            'payerId' => $PlayerID,
            'transactionReference' => $paymentId,
        ]);
        $response = $transaction->send();
        if ($response->isSuccessful()) {
            $data = $this->orderService->getDataOrderPaymentPaypal($orderKey);
            if(empty($data)) {
                throw new \Exception('Data order does not exist');
            }
            $data['order']['status'] = OrderStatus::Processing;
            $orderId = $this->orderService->createDataOrder($data['order'], $data['orderDetails'],true); 
            $this->orderService->forgetDataOrderPaymentPaypal($orderKey);
            return $orderId;
        } else {
            throw new \Exception($response->getMessage());
        }
    }
}
