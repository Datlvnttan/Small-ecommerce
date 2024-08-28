<?php

namespace Modules\Payment\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class PaypalRequest extends BaseRequest
{
    protected function methodGet()
    {
        return [
            'paymentId' => ['required', 'string'],
            'PayerID' => ['required', 'string'],
            'token' => ['required', 'string'],
        ];
    }
}
