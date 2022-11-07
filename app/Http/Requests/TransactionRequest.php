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
            'ref'=>'nullable|max:255',
            'credit_accounts'=>'required|array|min:1',
            'credit_accounts.*.account_id'=>'required|exists:accounts,id',
            'credit_accounts.*.amount'=>'required|numeric|min:0|not_in:0',
            'debit_accounts'=>'required|array|min:1',
            'debit_accounts.*.account_id'=>'required|exists:accounts,id',
            'debit_accounts.*.amount'=>'required|numeric|min:0|not_in:0',
        ];
    }

    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $validator->after(function ($validator) {
               
                if (!$this->isCreditDebitBalance()){
                    $validator->errors()->add('amount','Total of credit and debit accounts must be equal');
                }
            });
        }
    }

    private function isCreditDebitBalance(){
        $debit = $this->calArrayTotal($this->debit_accounts);
        $credit = $this->calArrayTotal($this->credit_accounts);
        return $debit == $credit;
    }

    private function calArrayTotal($array){
        $amount = 0;
        foreach($array as $item){
            $amount += $item['amount'];
        }
        return $amount;
    }
}
