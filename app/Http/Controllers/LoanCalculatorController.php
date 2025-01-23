<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanCalculatorRequest;
use App\Services\LoanCalculatorService;
use App\Models\LoanAmortizationSchedule;

class LoanCalculatorController extends Controller
{
    private $calculatorService;

    public function __construct(LoanCalculatorService $calculatorService)
    {
        $this->calculatorService = $calculatorService;
    }

    public function calculate(LoanCalculatorRequest $request)
    {
        $result = $this->calculatorService->generateAmortizationSchedule(
            $request->loan_amount,
            $request->annual_interest_rate,
            $request->loan_term_years,
            $request->extra_payments ?? []
        );

        foreach ($result['schedule'] as $entry) {
            LoanAmortizationSchedule::create([
                'month_number' => $entry['month'],
                'starting_balance' => $entry['starting_balance'],
                'monthly_payment' => $entry['monthly_payment'],
                'principal_component' => $entry['principal_payment'],
                'interest_component' => $entry['interest_payment'],
                'extra_payment' => $entry['extra_payment'],
                'ending_balance' => $entry['ending_balance']
            ]);
        }

        return response()->json($result);
    }
}