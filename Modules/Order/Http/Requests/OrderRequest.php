<?php

namespace Modules\Order\Http\Requests;

use App\Http\Requests\FailedReturnJsonFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderRequest extends FailedReturnJsonFormRequest
{
    public function methodGet()
    {
        return [
            'orderKey' => ['required', 'numeric', 'exists:Modules\Order\Entities\Order,order_key'],
        ];
    }
    public function methodPatch()
    {
        return [
            'reason' => ['required', 'string'],
        ];
    }
    public function methodPost()
    {
        $otherAddr = request()->get('otherAddr');
        $user = Auth::user();
        // dd(request()->get('otherAddr'));
        $validate = [
            'shippingMethodId' => ['required', 'numeric', 'exists:Modules\Shipping\Entities\ShippingMethod,id'],
            'paymentMethod' => ['required', 'string'],
            'deliveryPhoneNumber' => ['required', 'string'],
            'deliveryInternationalCallingCode' => ['required', 'string','exists:Modules\Shipping\Entities\Country,international_calling_code'],
        ];
        if (isset($user)) {
            if ($user->isNotDeliveryAddress()) {
                $validate = array_merge($validate, $this->validateFormDeliveryAddress());
            }
            else
            {
                $validate = array_merge($validate, [
                    'deliveryAddressId' => ['required', 'numeric', 'exists:Modules\User\Entities\DeliveryAddress,id'],
                ]);
            }
            if(isset($otherAddr))
            {
                if ($user->isNotBillingAddress()) {
                    $validate = array_merge($validate, $this->validateFormBillingAddress());
                }
                else
                {
                    $validate = array_merge($validate, [
                        'billingAddressId' => ['required','numeric', 'exists:Modules\User\Entities\BillingAddress,id'],
                    ]);
                }
            }
        } else {
            $validate = array_merge($validate, $this->validateFormDeliveryAddress(),[
                'deliveryEmail' => ['required', 'email']
            ]);
            // dd($otherAddr)
            if (isset($otherAddr)) {
                $validate = array_merge($validate, $this->validateFormBillingAddress());
            }
        }
        return $validate;
    }
    protected function validateFormDeliveryAddress()
    {
        return [
            'countryDeliveryAddressId' => ['required', 'numeric', 'exists:Modules\Shipping\Entities\Country,id'],
            'deliveryAddressSpecific' => ['required', 'string'],
            'deliveryDistrict' => ['required', 'string'],
            'deliveryFullname' => ['required', 'string'],
            // 'deliveryPhoneNumber' => ['required', 'string'],
            'deliveryProvince' => ['required', 'string'],
            'deliveryWard' => ['required', 'string'],
            'deliveryZipCode' => ['required', 'string'],
        ];
    }
    protected function validateFormBillingAddress()
    {
        return [
            'countryBillingAddressId' => ['required', 'numeric', 'exists:Modules\Shipping\Entities\Country,id'],
            'billingAddressSpecific' => ['required', 'string'],
            'billingDistrict' => ['required', 'string'],
            // 'billingEmail' => ['required', 'email'],
            'billingFullname' => ['required', 'string'],
            'billingProvince' => ['required', 'string'],
            'billingWard' => ['required', 'string'],
            'billingZipCode' => ['required', 'string'],
        ];
    }

}
