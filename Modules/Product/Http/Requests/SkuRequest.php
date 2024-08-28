<?php

namespace Modules\Product\Http\Requests;

use App\Http\Requests\FailedReturnJsonFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class SkuRequest extends FailedReturnJsonFormRequest
{
    public function methodGet()
    {
        return [
            'optionIds' => ['required', 'array', 'exists:Modules\Product\Entities\SkuProductAttributeOption,product_attribute_option_id'],
        ];
    }
}
