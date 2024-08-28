<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryAddressRequest extends AddressRequest
{
    protected function getPrefix()
    {
        return 'Delivery';
    }
    public function methodPost()
    {
        // dd($this->input('deliveryInternationalCallingCode'));
        return array_merge($this->rulesAccordingToSharedFields(),[
            'deliveryInternationalCallingCode' => ['required', 'string','exists:Modules\Shipping\Entities\Country,international_calling_code'],
            'deliveryPhoneNumber' => ['required', 'string'],
        ]);
    }
    public function methodPut()
    {
        return array_merge($this->rulesAccordingToSharedFields(),[
            'deliveryInternationalCallingCode' => ['required', 'string','exists:Modules\Shipping\Entities\Country,international_calling_code'],
            'deliveryPhoneNumber' => ['required', 'string'],
        ]);
    }
}
