<?php

namespace Modules\Order\Http\Requests;

use App\Http\Requests\FailedReturnJsonFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class DiscountCouponRequest extends FailedReturnJsonFormRequest
{
    public function methodGet()
    {
        return [
            'couponCode' => ['required', 'numeric', 'exists:Modules\Order\Entities\DiscountCoupon,code'],
        ];
    }
}
