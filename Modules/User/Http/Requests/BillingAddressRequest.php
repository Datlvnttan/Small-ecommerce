<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillingAddressRequest extends AddressRequest
{
    protected function getPrefix()
    {
        return 'Billing';
    }
    public function methodPost()
    {
        return $this->rulesAccordingToSharedFields();
    }
    public function methodPut()
    {
        return $this->rulesAccordingToSharedFields();
    }
}
