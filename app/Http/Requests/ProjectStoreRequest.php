<?php

namespace App\Http\Requests;

use App\Classes\ApiCatchErrors;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
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
            'name'=>'required|max:255|unique:projects,name,'.$this->projects,
            'total'=>'required|numeric|min:0|not_in:0',
            'start_date'=>'required|date',
            'due_date'=>'required|date',
        ];
    }
}
