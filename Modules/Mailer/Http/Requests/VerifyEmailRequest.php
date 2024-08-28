<?php

namespace Modules\Mailer\Http\Requests;

use App\Http\Requests\FailedReturnJsonFormRequest;

class VerifyEmailRequest extends FailedReturnJsonFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'otp_code' => 'required|numeric',
        ];
    }
    public function messages(): array
    {
        return [
            'otp_code.required' => 'OTP code cannot be left blank',
            'otp_code.numeric' => 'OTP code must be numeric',
        ];
    }  
}
