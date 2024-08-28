<?php

namespace Modules\Order\Http\Requests;

use App\Http\Requests\FailedReturnJsonFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class CancelOrderGuestRequest extends FailedReturnJsonFormRequest
{
    public function methodPost()
    {
        return [
            'otp' => ['required', 'numeric']
        ];
    }
}
