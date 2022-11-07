<?php

namespace App\Http\Requests;

use App\Classes\ApiCatchErrors;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class PeopleStoreRequest extends FormRequest
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
            'name'=>'required|max:255|unique:people,name,'.$this->people,
            'nick_name'=>'required|max:255|unique:people,nick_name,'.$this->people,
        ];
    }
}
