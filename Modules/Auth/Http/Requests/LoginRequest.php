<?php

namespace Modules\Auth\Http\Requests;

use App\Http\Requests\FailedReturnJsonFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FailedReturnJsonFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
    public function messages(): array
    {
        return [
            'email.required' => 'Email cannot be left blank',
            'password.required' => 'Password cannot be blank',
        ];
    }  
}
