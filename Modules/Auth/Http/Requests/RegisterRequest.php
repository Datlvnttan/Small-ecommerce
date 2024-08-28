<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fullname' => 'required|min:3',
            'email' => 'required|email',                     
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required',            
        ];
    }

    public function messages(): array
    {
        return [                      
            'fullname.required' => 'Full name cannot be blank',                
            'email.required' => 'Email cannot be blank',                
            'password_confirmation.required' => 'Please re-enter your password',             
            'email.email' => "The email address is not in the correct format",
            'password.confirmed' => "The re-entered password does not match",           
        ];
    }   
}
