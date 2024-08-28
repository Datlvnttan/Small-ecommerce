<?php

namespace Modules\User\Http\Requests;

use App\Http\Requests\FailedReturnJsonFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FailedReturnJsonFormRequest
{
    public function methodPut()
    {
        // exists:Modules\Product\Entities\SkuProductAttributeOption,
        return [
            'fullname' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:Modules\User\Entities\User,nickname,' . auth()->id()],
            'email' => ['required', 'max:255', 'unique:Modules\User\Entities\User,email,' . auth()->id()],
            'birthday' => ['date', 'before:today'],
            'gender' => ['in:Male,Female'],
        ];
    }

    public function methodPatch()
    {
        return [
            'oldPassword' => ['required', 'min:3'],
            'newPassword' => ['required', 'min:3', 'confirmed'],
            'newPassword_confirmation' => ['required'],
        ];
    }
}
