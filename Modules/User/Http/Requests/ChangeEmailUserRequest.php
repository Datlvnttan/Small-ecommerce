<?php

namespace Modules\User\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class ChangeEmailUserRequest extends BaseRequest
{
    public function methodPost()
    {
        return [
            'tokenChangeEmail' => ['required', 'string'],
            'password' => ['required', 'string'],
            'email' => ['required', 'string'],
        ];
    }

    public function methodGet()
    {
        return [
            'tokenChangeEmail' => ['required','string']
        ];
    }
}
