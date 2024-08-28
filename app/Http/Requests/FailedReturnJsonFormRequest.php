<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
class FailedReturnJsonFormRequest extends BaseRequest
{
    // /**
    //  * Determine if the user is authorized to make this request.
    //  */
    // public function authorize(): bool
    // {
    //     return false;
    // }
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = new JsonResponse(['message' => 'Validation failed', 'errors' => $errors], 422);
        throw new ValidationException($validator, $response);                
    }  
}
