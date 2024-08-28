<?php

namespace Modules\User\Http\Requests;

use App\Http\Requests\FailedReturnJsonFormRequest;
use Illuminate\Foundation\Http\FormRequest;

abstract class AddressRequest extends FailedReturnJsonFormRequest
{
    protected $prefix;
    protected $prefixNameLower;
    public function __construct()
    {
        $this->prefix = $this->getPrefix();
        $this->prefixNameLower = strtolower($this->prefix);
    }
    protected function rulesAccordingToSharedFields()
    {
        return
            [
                 "{$this->prefixNameLower}Fullname" => ['required', 'string'],
                 "country{$this->prefix}AddressId" => ['required', 'integer', 'exists:Modules\Shipping\Entities\Country,id'],
                 "{$this->prefixNameLower}AddressSpecific" => ['required', 'string'],
                 "{$this->prefixNameLower}Province" => ['required', 'string'],
                 "{$this->prefixNameLower}District" => ['required', 'string'],
                 "{$this->prefixNameLower}Ward" => ['required', 'string'],
                 "{$this->prefixNameLower}ZipCode" => ['required', 'string'],
                 "{$this->prefixNameLower}Default" => ['required','bool'],
            ];
    }
    abstract protected function getPrefix();
    protected function prepareForValidation()
    {
        $this->merge([
            "{$this->prefixNameLower}Default" => $this->input("{$this->prefixNameLower}Default", false),
        ]);
    }
}
