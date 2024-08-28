<?php

namespace Modules\Order\Services;

use App\Helpers\Helper;
use App\Helpers\ResponseJson;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Order\Repositories\Interface\OrderRepositoryInterface;
use Illuminate\Support\Str;
use Modules\Cart\Services\CartService;
use Modules\Mailer\Services\MailService;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Repositories\Interface\DiscountCouponRepositoryInterface;
use Modules\Order\Repositories\Interface\OrderDetailRepositoryInterface;
use Modules\Payment\Services\PayPalService;
use Modules\Product\Repositories\Interface\SkuRepositoryInterface;
use Modules\Product\Services\SkuService;
use Modules\Shipping\Entities\Country;
use Modules\Shipping\Repositories\Interface\CountryRepositoryInterface;
use Modules\Shipping\Repositories\Interface\ShippingMethodCountryRepositoryInterface;
use Modules\Shipping\Repositories\Interface\ShippingMethodRepositoryInterface;
use Modules\User\Repositories\Interface\BillingAddressRepositoryInterface;
use Modules\User\Repositories\Interface\DeliveryAddressRepositoryInterface;

class OrderService
{
    public const TIME_OUT = 10 * 6000000; //thời gian hết hạn data đơn hàng
    public const ORDER_PAYMENT_PREFIX = 'order-';
    public const ORDER_VERIFY_PREFIX = 'email-verify-';
    public const ORDER_CHECKOUT_KEY_PREFIX = 'checkout-';
    public const ORDER_TOKEN_CANCEL_ORDER_GUEST_PREFIX = 'cancel-order-guest-';
    protected OrderRepositoryInterface $orderRepositoryInterface;
    protected OrderDetailRepositoryInterface $orderDetailRepositoryInterface;
    protected DeliveryAddressRepositoryInterface $deliveryAddressRepositoryInterface;
    protected BillingAddressRepositoryInterface $billingAddressRepositoryInterface;
    protected DiscountCouponRepositoryInterface $discountCouponRepositoryInterface;
    protected ShippingMethodRepositoryInterface $shippingMethodRepositoryInterface;
    protected ShippingMethodCountryRepositoryInterface $shippingMethodCountryRepositoryInterface;
    protected CountryRepositoryInterface $countryRepositoryInterface;
    protected SkuRepositoryInterface $skuRepositoryInterface;
    protected CartService $cartService;
    protected MailService $mailService;
    protected SkuService $skuService;

    public function __construct(
        OrderRepositoryInterface $orderRepositoryInterface,
        OrderDetailRepositoryInterface $orderDetailRepositoryInterface,
        DeliveryAddressRepositoryInterface $deliveryAddressRepositoryInterface,
        BillingAddressRepositoryInterface $billingAddressRepositoryInterface,
        DiscountCouponRepositoryInterface $discountCouponRepositoryInterface,
        ShippingMethodRepositoryInterface $shippingMethodRepositoryInterface,
        ShippingMethodCountryRepositoryInterface $shippingMethodCountryRepositoryInterface,
        CountryRepositoryInterface $countryRepositoryInterface,
        SkuRepositoryInterface $skuRepositoryInterface,
        CartService $cartService,
        MailService $mailService,
        SkuService $skuService
    ) {
        $this->orderRepositoryInterface = $orderRepositoryInterface;
        $this->orderDetailRepositoryInterface = $orderDetailRepositoryInterface;
        $this->deliveryAddressRepositoryInterface = $deliveryAddressRepositoryInterface;
        $this->billingAddressRepositoryInterface = $billingAddressRepositoryInterface;
        $this->discountCouponRepositoryInterface = $discountCouponRepositoryInterface;
        $this->shippingMethodRepositoryInterface = $shippingMethodRepositoryInterface;
        $this->shippingMethodCountryRepositoryInterface = $shippingMethodCountryRepositoryInterface;
        $this->countryRepositoryInterface = $countryRepositoryInterface;
        $this->skuRepositoryInterface = $skuRepositoryInterface;
        $this->cartService = $cartService;
        $this->mailService = $mailService;
        $this->skuService = $skuService;
    }
    public function findById($id)
    {
        return $this->orderRepositoryInterface->find($id);
    }
    public function buildDataOrder($cartItems, $shippingMethodId)
    {
        foreach ($cartItems as $cartItem) {
            if ($cartItem->sku_quantity < $cartItem->cart_quantity) {
                throw new \Exception("The quantity of \"{$cartItem->product_name} - {$cartItem->options}\" in stock is insufficient({$cartItem->sku_quantity}).");
            }
        }
        $key = Str::random(100);
        $dataOrder = [
            'orderDetails' => $cartItems,
            'shippingMethodId' => $shippingMethodId,
            'createdAt' => now(),
        ];
        Session::put($this->buildCheckoutKey($key), $dataOrder);
        return route('web.order.checkout', ['key' => $key]);
    }
    public function buildCheckoutKey($key)
    {
        return OrderService::ORDER_CHECKOUT_KEY_PREFIX . $key;
    }
    public function getDataOrder(string $key)
    {
        $dataOrder = Session::get($this->buildCheckoutKey($key));
        if (!$dataOrder || (time() - strtotime($dataOrder['createdAt'])) > (self::TIME_OUT)) {
            return null;
        }
        return $dataOrder;
    }
    public function cleanDataOrder($key)
    {
        $checkoutKey = $this->buildCheckoutKey($key);
        if (Session::has($checkoutKey)) {
            Session::remove($checkoutKey);
        } else {
            throw new \Exception('This page does not exist');
        }
    }
    public function submitDataOrder($orderDetailsTmp, $data, $user, $checkoutKey)
    {
        return DB::transaction(function () use ($orderDetailsTmp, $data, $user, $checkoutKey) {
            $discountCoupon = null;
            $order = null;
            $countryId = null;
            $countryDeliveryAddress = null;
            $countryBillingAddress = null;
            $totalAmount = 0;
            // $shippingMethod = $this->shippingMethodRepositoryInterface->find($data['shippingMethodId']);
            //nếu đã đăng nhập 
            if (isset($user)) {
                $order['user_id'] = $user->id;
                $status = OrderStatus::AwaitingConfirmation;
                //Nếu user chưa tạo địa chỉ giao hàng
                if ($user->isNotDeliveryAddress()) {
                    $countryId = $data['countryDeliveryAddressId'];
                    $countryDeliveryAddress = $this->countryRepositoryInterface->find($countryId);
                    $deliveryAddressJson = $this->buildAddressOrder(
                        $data['deliveryFullname'],
                        $countryDeliveryAddress->country_name,
                        $data['deliveryAddressSpecific'],
                        $data['deliveryProvince'],
                        $data['deliveryDistrict'],
                        $data['deliveryWard'],
                        $data['deliveryZipCode'],
                    );
                } //Nếu user đã tạo ít nhất 1 địa chỉ giao hàng
                else {
                    $deliveryAddressId = $data['deliveryAddressId'];
                    $deliveryAddress = $this->deliveryAddressRepositoryInterface->find($deliveryAddressId);
                    $deliveryAddressJson = $this->buildAddressOrder(
                        $deliveryAddress->fullname,
                        $deliveryAddress->country->country_name,
                        $deliveryAddress->address_specific,
                        $deliveryAddress->province,
                        $deliveryAddress->district,
                        $deliveryAddress->ward,
                        $deliveryAddress->zip_code
                    );
                    $countryId = $deliveryAddress->country_id;
                }
                //Nếu người dùng chọn thêm địa chỉ thanh toán khác
                if (isset($data['otherAddr'])) {
                    //Nếu người dùng chưa có địa chỉ thanh toán khác
                    if ($user->isNotBillingAddress()) {
                        $countryBillingAddress = $this->countryRepositoryInterface->find($data['countryBillingAddressId']);
                        $billingAddressJson = $this->buildAddressOrder(
                            $data['billingFullname'],
                            $countryBillingAddress->country_name,
                            $data['billingAddressSpecific'],
                            $data['billingProvince'],
                            $data['billingDistrict'],
                            $data['billingWard'],
                            $data['billingZipCode'],
                        );
                        if (isset($data['billingTaxIDNumber'])) {
                            $billingAddressJson['tax_id_number'] = $data['billingTaxIDNumber'];
                        }
                    } //Nếu người dùng đã có ít nhất 1 địa chỉ thanh toán khác
                    else {
                        $billingAddress = $this->billingAddressRepositoryInterface->find($data['billingAddressId']);
                        $billingAddressJson = $this->buildAddressOrder(
                            $billingAddress->fullname,
                            $billingAddress->country->country_name,
                            $billingAddress->address_specific,
                            $billingAddress->province,
                            $billingAddress->district,
                            $billingAddress->ward,
                            $billingAddress->zip_code,
                        );
                        if (isset($billingAddress->tax_id_number)) {
                            $billingAddressJson['tax_id_number'] = $billingAddress->tax_id_number;
                        }
                    }
                    // Nếu người dùng chọn địa chỉ giao hàng cũng là địa chỉ thanh toán
                } else {
                    $billingAddressJson = $deliveryAddressJson;
                }
                $order['email'] = $user->email;
                // Nếu chưa đăng nhập
            } else {
                $status = OrderStatus::AwaitingVerification;
                $countryId = $data['countryDeliveryAddressId'];
                $countryDeliveryAddress = $this->countryRepositoryInterface->find($countryId);
                $deliveryAddressJson = $this->buildAddressOrder(
                    $data['deliveryFullname'],
                    $countryDeliveryAddress->country_name,
                    $data['deliveryAddressSpecific'],
                    $data['deliveryProvince'],
                    $data['deliveryDistrict'],
                    $data['deliveryWard'],
                    $data['deliveryZipCode'],
                );
                if (isset($data['otherAddr'])) {
                    $countryBillingAddress = $this->countryRepositoryInterface->find($data['countryBillingAddressId']);
                    $billingAddressJson = $this->buildAddressOrder(
                        $data['billingFullname'],
                        $countryBillingAddress->country_name,
                        $data['billingAddressSpecific'],
                        $data['billingProvince'],
                        $data['billingDistrict'],
                        $data['billingWard'],
                        $data['billingZipCode'],
                    );
                    if (isset($data['billingTaxIDNumber'])) {
                        $billingAddressJson['tax_id_number'] = $data['billingTaxIDNumber'];
                    }
                } else {
                    $billingAddressJson = $deliveryAddressJson;
                }
                $order['email'] = $data['deliveryEmail'];
            }
            $internationalCallingCode = $data['deliveryInternationalCallingCode'];
            $country = $this->countryRepositoryInterface->findByInternationalCallingCode($internationalCallingCode);
            //Xóa số 0 đầu số điện thoại nếu có
            // $formattedPhoneNumber = preg_replace('/^0/', '', $data['deliveryPhoneNumber']);
            $deliveryAddressJson['phone_number'] = $data['deliveryPhoneNumber'];
            $deliveryAddressJson['international_calling_code'] = $country->international_calling_code;
            $shippingMethodCountry = $this->shippingMethodCountryRepositoryInterface->find([
                'country_id' => $countryId,
                'shipping_method_id' => $data['shippingMethodId'],
            ]);
            if (!isset($shippingMethodCountry)) {
                throw new \Exception('The delivery method you have selected is not supported in this country');
            }
            $shippingMethodJson = [
                'shipping_method_name' => $shippingMethodCountry->shippingMethod->shipping_method_name,
                'expense' => $shippingMethodCountry->expense,
                'discount' => $shippingMethodCountry->discount,
                'delivery_time' => $shippingMethodCountry->delivery_time,
            ];
            $shippingFee = $shippingMethodCountry->expense * (1 - $shippingMethodCountry->discount);
            $discountCouponValue = 0;
            if (isset($data['couponCode'])) {
                $discountCoupon = $this->discountCouponRepositoryInterface->getByCouponCodeStillInUse($data['couponCode']);
                if (!isset($discountCoupon)) {
                    throw new \Exception('Coupon code does not exist or has expired');
                }
                $discountCouponJson = [
                    'coupon_code' => $discountCoupon->coupon_code,
                    'discount' => $discountCoupon->discount,
                ];
                $order["discount_coupon"] = $discountCouponJson;
                $discountCoupon->quantity -= 1;
                $discountCoupon->save();
                $discountCouponValue = $discountCoupon->discount;
            }
            if (isset($data['note'])) {
                $order["note"] = $data['note'];
            }
            $order["shipping_method"] = $shippingMethodJson;
            $order["payment_method"] = $data['paymentMethod'];
            $order["delivery_address"] = $deliveryAddressJson;
            $order["billing_address"] = $billingAddressJson;
            // $orderEncoded = base64_encode(json_encode($order));
            // $orderDecode = json_decode(base64_decode($orderEncoded));
            if (isset($user)) {
                //Lưu lại địa chỉ nhận hàng mới
                if (isset($data['saveDeliveryAddress'])) {
                    $deliveryAddressJson['user_id'] = $user->id;
                    $deliveryAddressJson['country_id'] = $countryDeliveryAddress->id;
                    $deliveryAddressJson['default'] = true;
                    $this->deliveryAddressRepositoryInterface->create($deliveryAddressJson);
                }
                //Lưu lại địa chỉ thanh toán
                if (isset($data['saveBillingAddress'])) {
                    $billingAddressJson['user_id'] = $user->id;
                    $billingAddressJson['country_id'] = $countryBillingAddress->id;
                    $billingAddressJson['default'] = true;
                    $this->billingAddressRepositoryInterface->create($billingAddressJson);
                }
            }
            $orderDetails = [];
            $totalPoints = 0;
            // $skuIds = array_map(function ($orderDetail) {
            //     return $orderDetail['sku_id'];
            // }, $orderDetailsTmp);
            $skuIds = $orderDetailsTmp->pluck('sku_id')->toArray();
            // return $skuIds;
            $skus = $this->skuRepositoryInterface->getBySkuIds($skuIds);
            foreach ($orderDetailsTmp as $item) {
                $totalPoints += $item['shipping_point'];
                $cartQuantity = intval($item['cart_quantity']);
                // $price = floatval($item['price'] - $item['price'] * $item['discount']);
                $orderDetailPriceNew = Helper::getPriceNew($item);
                foreach ($skus as $sku) {
                    $skuPriceNew = Helper::getPriceNew($sku);
                    if ($sku->sku_id == $item->sku_id) {
                        if ($sku->sku_quantity < $cartQuantity) {
                            $this->cleanDataOrder($checkoutKey);
                            throw new \Exception("Product \"{$item['product_name']} - {$item['options']}\" out of stock, please check your cart again",Response::HTTP_CONFLICT);
                        } elseif ($orderDetailPriceNew != $skuPriceNew) {
                            $this->cleanDataOrder($checkoutKey);
                            throw new \Exception("Product \"{$item['product_name']} - {$item['options']}\" has been changed price, please check your cart again",Response::HTTP_CONFLICT);
                        }
                        break;
                    }
                }
                array_push($orderDetails, [
                    'sku_id' => $item['sku_id'],
                    'price' => $orderDetailPriceNew,
                    'options' => $item['options'],
                    'quantity' => $cartQuantity,
                ]);
                $totalAmount += $orderDetailPriceNew * $cartQuantity;
            }
            $order["total_point"] = $totalPoints;
            $order["total_amount"] = ($totalAmount - $totalAmount * $discountCouponValue) + $shippingFee;
            $orderKey = time();
            $order['order_key'] = $orderKey;
            // return $order;
            if ($order['payment_method'] == 'Paypal') {
                $this->setUpOrderPaymentPaypal($orderKey, $order, $orderDetails);
                $paypalService = new PayPalService();
                $response = $paypalService->getUrlPaymentPayPal($orderKey, $order["total_amount"], $checkoutKey);
                return $response;
            } else {
                $order['status'] = $status;
            }
            $orderId = $this->createDataOrder($order, $orderDetails);
            return route('web.order.checkout.order-success', [
                'orderId' => $orderId,
                'orderKey' => $orderKey,
                'checkoutKey' => $checkoutKey,
            ]);
        });
    }
    public function retryOrderPayment($checkoutKey, $dataOrder)
    {
        $orderKey = time();
        $order = $dataOrder['order'];
        $orderDetails = $dataOrder['orderDetails'];
        $order['order_key'] = $orderKey;
        $this->setUpOrderPaymentPaypal($orderKey, $order, $orderDetails);
        $paypalService = new PayPalService();
        $response = $paypalService->getUrlPaymentPayPal($orderKey, $order["total_amount"], $checkoutKey);
        return $response;
    }
    private function setUpOrderPaymentPaypal($orderKey, $order, $orderDetails)
    {
        $orderKey = $this->buildOrderKey($orderKey);
        Session::put($orderKey, [
            'order' => $order,
            'orderDetails' => $orderDetails,
            'createdAt' => now()
        ]);
    }
    public function buildOrderKey($orderKey)
    {
        return OrderService::ORDER_PAYMENT_PREFIX . $orderKey;
    }
    public function getDataOrderPaymentPaypal($orderKey)
    {
        return Session::pull($this->buildOrderKey($orderKey));
    }
    public function forgetDataOrderPaymentPaypal($orderKey)
    {
        return Session::forget($this->buildOrderKey($orderKey));
    }
    public function createDataOrder($order, $orderDetails, $isPaid = false)
    {
        try {
            return DB::transaction(function () use ($order, $orderDetails, $isPaid) {
                $order['is_paid'] = $isPaid;
                $newOrder = $this->orderRepositoryInterface->create($order);
                $orderId = $newOrder->id;
                $countOrderDetails = count($orderDetails);
                for ($i = 0; $i <  $countOrderDetails; $i++) {
                    $orderDetails[$i]['order_id'] = $orderId;
                }
                $this->orderDetailRepositoryInterface->inserts($orderDetails);
                $mailService = new MailService(null);
                $deliveryAddress = $newOrder->delivery_address;
                if ($newOrder->current_status === OrderStatus::AwaitingVerification) {
                    $tokenOrderVerify = Str::random(50);
                    $this->putTokenOrderVerify($newOrder->order_key, $tokenOrderVerify);
                    $mailService->sendOrderConfirmationEmail($newOrder->email, $deliveryAddress->fullname, $newOrder->order_key, $tokenOrderVerify);
                } else {
                    $mailService->sendOrderSuccessNotification($newOrder->email, $deliveryAddress->fullname, $newOrder, $orderDetails);
                }
                if ($isPaid) {
                    $this->skuService->updateInventoryFromOrderItems($newOrder->id, false);
                }
                $this->cartService->cleanItemsByOrderDetails($orderDetails, $newOrder->user_id);
                return $orderId;
            });
        } catch (\Exception $e) {
            if (isset($order['discount_coupon'])) {
                $discountCoupon = $order['discount_coupon'];
                $discountCoupon = $this->discountCouponRepositoryInterface->findByCouponCode($discountCoupon['coupon_code']);
                if (isset($discountCoupon)) {
                    $discountCoupon->quantity += 1;
                    $discountCoupon->save();
                }
            }
            throw $e;
        }
    }
    protected function buildAddressOrder(String $fullname, $countryName, string $addressSpecific, string $province, string $district, string $ward, string $zipCode)
    {
        return [
            'fullname' => $fullname,
            'country' => $countryName,
            'address_specific' => $addressSpecific,
            'province' => $province,
            'district' => $district,
            'ward' => $ward,
            'zip_code' => $zipCode,
        ];
    }
    protected function getTokenOrderVerify($orderKey)
    {
        return Session::get(OrderService::ORDER_VERIFY_PREFIX . $orderKey);
    }
    public function putTokenOrderVerify($orderKey, $tokenOrderVerify)
    {
        Session::put(OrderService::ORDER_VERIFY_PREFIX . $orderKey, $tokenOrderVerify);
    }
    public function removeTokenOrderVerify($orderKey)
    {
        Session::remove(OrderService::ORDER_VERIFY_PREFIX . $orderKey);
    }
    public function verifyOrder($orderKey, $requestTokenOrderVerify)
    {
        return DB::transaction(function () use ($orderKey, $requestTokenOrderVerify) {
            $order = $this->orderRepositoryInterface->getByOrderKey($orderKey);
            // return OrderStatus::AwaitingVerification;
            if (!isset($order)) {
                throw new \Exception('Order not found');
            }
            // dd($order->current_status);
            // $statusAwaitingVerification = OrderStatus::AwaitingVerification;
            if ($order->current_status === OrderStatus::AwaitingVerification) {
                $tokenOrderVerify = $this->getTokenOrderVerify($order->order_key);
                if ($tokenOrderVerify != $requestTokenOrderVerify) {
                    throw new \Exception('Token not valid');
                }
                // $status = $order->status;
                // $newStatus = OrderStatus::awaitingVerification
                // $status = array_merge($status,[
                //     OrderStatus::awaitingVerification => now()
                // ]);
                $order->status = OrderStatus::AwaitingConfirmation;
                $order->save();
                $this->removeTokenOrderVerify($orderKey);
                return true;
            }
            throw new \Exception('Currently orders do not require verification');
        });
    }
    public function getOrderDetailsByOrderId($orderId)
    {
        $order = $this->orderRepositoryInterface->getOrderDetailsByOrderId($orderId);
        if (isset($order)) {
            return $order;
        }
        throw new \Exception('The order does not exist or you do not have access');
    }
    public function findByOrderKey($orderKey)
    {
        return $this->orderRepositoryInterface->getByOrderKey($orderKey);
    }
    public function cancelOrder($orderId, $reason)
    {
        $order = $this->orderRepositoryInterface->find($orderId);
        if (isset($order)) {
            if (!$order->is_allowed_cancel) {
                throw new \Exception('This order is not allowed to be cancelled');
            }
            if ($order->orderWithLogin()) {
                if ($order->checkAccess()) {
                    return $this->handleCancelOrder($order, $reason);
                }
            }
            return $this->cancelOrderGuest($order, $reason);
        }
        throw new \Exception('The order does not exist');
    }
    protected function handleCancelOrder($order, $reason)
    {
        return Helper::DBTransaction(function () use ($order, $reason) {
            $status = $order->status;
            // 'Cancelled' => [
            //     'at' => now(),
            //     'reason' => $reason,
            // ];
            $order->status = [
                'Cancelled' => [
                    'at' => now(),
                    'reason' => $reason,
                ]
            ];
            $order->save();
            return 1;
        });
    }
    protected function getKeySessionCancelOrderGuest($orderId)
    {
        return self::ORDER_TOKEN_CANCEL_ORDER_GUEST_PREFIX . $orderId;
    }
    protected function putDataCancelOrder($orderId, string $reason, string $otp)
    {
        Session::put($this->getKeySessionCancelOrderGuest($orderId), [
            'otp' => $otp,
            'reason' => $reason,
            'createdAt' => now()
        ]);
    }
    protected function getDataCancelOrder($orderId)
    {
        return Session::get($this->getKeySessionCancelOrderGuest($orderId));
    }
    protected function removeDataCancelOrder($orderId)
    {
        return Session::remove($this->getKeySessionCancelOrderGuest($orderId));
    }
    protected function cancelOrderGuest(Order $order, $reason)
    {
        $order->setMasked(false);
        $diliveryAddress = $order->getDeliveryAddressAttribute();
        $otp = Helper::randomOTPNumeric();
        $this->putDataCancelOrder($order->id, $reason, $otp);
        $this->mailService->sendEmailVerifyCancelOrderGuest($order->email, $diliveryAddress->fullname, $order->id, $otp);
        return 2;
    }
    public function verifyCancelOrderGuest($orderId, $otp)
    {
        $order = $this->orderRepositoryInterface->find($orderId);
        if (!$order) {
            throw new \Exception('The order does not exist', Response::HTTP_NOT_FOUND);
        }
        $data = $this->getDataCancelOrder($orderId);
        if (!$data) {
            throw new \Exception('This order has no cancellation request');
        }
        if (time() - strtotime($data['createdAt']) > (self::TIME_OUT)) {
            $this->cancelOrderGuest($order, $data['reason']);
            throw new \Exception('The OTP code has expired, we have sent you a new code');
        }
        if ($data['otp'] !== $otp) {
            throw new \Exception('OTP not valid', Response::HTTP_UNAUTHORIZED);
        }
        $success = $this->handleCancelOrder($order, $data['reason']);
        $this->removeDataCancelOrder($orderId);

        return $success;
    }
    public function resendEmailCancelOrderGuest($orderId)
    {
        $order = $this->orderRepositoryInterface->find($orderId);
        if (!$order) {
            throw new \Exception('The order does not exist', Response::HTTP_NOT_FOUND);
        }
        $data = $this->getDataCancelOrder($orderId);
        if (!$data) {
            throw new \Exception('This order has no cancellation request');
        }
        if (time() - strtotime($data['createdAt']) < 60) {
            throw new \Exception('The operation is too fast, please wait');
        }
        $this->cancelOrderGuest($order, $data['reason']);
    }
}
