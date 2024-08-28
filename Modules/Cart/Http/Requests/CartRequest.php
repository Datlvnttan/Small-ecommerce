<?php

namespace Modules\Cart\Http\Requests;

use App\Http\Requests\FailedReturnJsonFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FailedReturnJsonFormRequest
{
    public function methodPost()
    {
        return [
            'skuId' => ['numeric', 'exists:Modules\Product\Entities\Sku,id'],
            'productId' => ['required', 'numeric', 'exists:Modules\Product\Entities\Product,id'],
            'quantity' =>['numeric','min:1']
        ];
    }
    public function methodPut()
    {
        return [
            'quantity' => ['required', 'numeric'],
            'skuId' => ['numeric', 'exists:Modules\Product\Entities\Sku,id'],
        ];
    }
    public function methodDelete()
    {
        return [
            'skuId' => ['numeric', 'exists:Modules\Product\Entities\Sku,id'],
        ];
    }
}
