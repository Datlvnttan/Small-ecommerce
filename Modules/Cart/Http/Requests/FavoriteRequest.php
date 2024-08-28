<?php

namespace Modules\Cart\Http\Requests;

use App\Http\Requests\FailedReturnJsonFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class FavoriteRequest extends FailedReturnJsonFormRequest
{
    public function methodPost()
    {
        return [
            'productId' => ['required', 'numeric', 'exists:Modules\Product\Entities\Product,id'],
        ];
    }
}
