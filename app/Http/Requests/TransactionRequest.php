<?php

namespace App\Http\Requests;

use App\Classes\ApiCatchErrors;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'type'=>'required|in:income,expense',
            'date'=>'required|date',
            'description'=>'required|max:255',
            'account_id'=>'required|exists:accounts,id',
            'person_id'=>'required|exists:people,id',
            'category_id'=>'required|exists:categories,id',
            'project_id'=>'nullable|exists:projects,id',
            'ref'=>'nullable|max:255',
            'amount'=>'required|numeric|min:0|not_in:0',
            'type'=>'required|in:income,expense',
        ];
    }
}
