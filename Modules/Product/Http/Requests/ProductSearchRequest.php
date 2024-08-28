<?php

namespace Modules\Product\Http\Requests;

use App\Http\Requests\FailedReturnJsonFormRequest;
use Illuminate\Contracts\Validation\Validator;

class ProductSearchRequest extends FailedReturnJsonFormRequest
{
    public function methodGet()
    {
        return [
            'c' => ['integer', 'exists:Modules\Product\Entities\Category,id'],
            'b' => ['integer', 'exists:Modules\Product\Entities\Brand,id'],
            'sort' => ['required', 'in:score,hot,rating,p-asc,p-desc,az,za'],
            'sv' => ['array'],
            // 'sn' => ['array'],
            'fz' => ['required', 'boolean'],
            'lf' => ['required', 'boolean'],
            'minPrice' => ['numeric', 'min:0'],
            'maxPrice' => ['numeric', 'min:0'],
            'new' => ['required', 'boolean'],
            'sale' => ['required', 'boolean'],
            'sbs' => ['required', 'boolean'],
        ];
    }
    protected function prepareForValidation()
    {
        $minPrice = $this->input("minPrice");
        if(!isset($minPrice))
        {
            $minPrice = 0;
        }
        $this->merge([
            "fz" => boolval($this->input("fz", true)),
            "lf" => boolval($this->input("lf", false)),
            "new" => boolval($this->input("new", false)),
            "sort" => $this->input("sort", 'score'),
            "page" => $this->input("page", 1),
            "sale" => boolval($this->input("sale", false)),
            "sbs" => $this->input("sbs", false),
            "minPrice" => $minPrice
            // "maxPrice" => $this->input("maxPrice",999999999999999),
        ]);
    }
    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $maxPrice = $this->input('maxPrice');
            if(isset($maxPrice))
            {
                $minPrice = $this->input('minPrice');
                if ($minPrice > $maxPrice) {
                    $validator->errors()->add('minPrice', 'The minimum price must be less than the maximum price.');
                }
            }

            
        });
    }
}
