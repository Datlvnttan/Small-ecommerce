<?php

namespace Modules\Product\Http\Requests;

use App\Http\Requests\FailedReturnJsonFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class FilterProductRequest extends FailedReturnJsonFormRequest
{
    public function methodGet()
    {
        return [
            'parentCategoryId' => ['integer','exists:Modules\Product\Entities\Category,id'],
            'sort' => ['required', 'in:hot,rating,p-asc,p-desc,az,za'],
            'priceRange' => ['required', 'boolean'],
            'minPrice' => ['numeric','min:0'],
            'maxPrice' => ['numeric','min:0'],
            'new' => ['required', 'boolean'],
            'sale' => ['required', 'boolean'],
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            "new" => $this->input("new", false),
            "sale" => $this->input("sale", false),
            "priceRange" => $this->input("priceRange", false),
            "minPrice" => $this->input("minPrice", 0),
            // "maxPrice" => $this->input("maxPrice",999999999999999),
        ]);
    }
}
