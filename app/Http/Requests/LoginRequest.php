<?php

namespace App\Http\Requests;

use App\Classes\ApiCatchErrors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class LoginRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        return ApiCatchErrors::validationError($validator);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'=>'required',
            'password'=>'required'
        ];
    }
}
