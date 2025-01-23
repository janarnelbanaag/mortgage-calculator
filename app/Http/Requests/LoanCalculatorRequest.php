<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanCalculatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'loan_amount' => 'required|numeric|min:1',
            'annual_interest_rate' => 'required|numeric|min:0.01|max:100',
            'loan_term_years' => 'required|integer|min:1|max:50',
            'extra_payments' => 'nullable|array',
            'extra_payments.*.amount' => 'nullable|numeric|min:1',
            'extra_payments.*.month' => 'nullable|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'loan_amount.min' => 'Loan amount must be greater than 0',
            'annual_interest_rate.min' => 'Interest rate must be greater than 0%',
            'annual_interest_rate.max' => 'Interest rate cannot exceed 100%',
            'loan_term_years.min' => 'Loan term must be at least 1 year',
            'loan_term_years.max' => 'Loan term cannot exceed 50 years'
        ];
    }
}
