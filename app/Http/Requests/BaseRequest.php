<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
       /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return match($this->method()) {
            'GET' => $this->methodGet(),
            'POST' => $this->methodPost(),
            'PUT' => $this->methodPut(),
            'PATCH' => $this->methodPatch(),
            'DELETE' => $this->methodDelete(),
            'OPTIONS' => $this->methodOptions(),
            default => $this->methodGet()
        };
    }
    // protected function prepareForValidation()
    // {
    //     $merge= match($this->method()) {
    //         'GET' => $this->prepareMethodGet(),
    //         'POST' => $this->prepareMethodPost(),
    //         'PUT' => $this->prepareMethodPut(),
    //         'PATCH' => $this->prepareMethodPatch(),
    //         'DELETE' => $this->prepareMethodDelete(),
    //         'OPTIONS' => $this->prepareMethodOptions(),
    //         default => $this->prepareMethodGet()
    //     };
    //     $this->merge($merge);
    // }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function methodGet()
    {
        return [

        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function methodPost()
    {
        return [
            
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function methodPut()
    {
        return [
            
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function methodPatch()
    {
        return [
            
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function methodDelete()
    {
        return [
            
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function methodOptions()
    {
        return [
            
        ];
    }

    // protected function prepareMethodGet()
    // {
    //     return [

    //     ];
    // }
}
