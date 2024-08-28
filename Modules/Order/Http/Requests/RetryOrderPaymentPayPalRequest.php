<?php

namespace Modules\Order\Http\Requests;

use App\Http\Requests\FailedReturnJsonFormRequest;
use Illuminate\Auth\Events\Failed;
use Illuminate\Foundation\Http\FormRequest;

class RetryOrderPaymentPayPalRequest extends FailedReturnJsonFormRequest
{
    public function methodPost()
    {
        return [
            'orderKey' => ['required', 'string'],
        ];
    }
}
