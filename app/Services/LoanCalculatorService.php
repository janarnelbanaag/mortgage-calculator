<?php

namespace App\Services;

class LoanCalculatorService
{
    public function calculateMonthlyPayment(float $loanAmount, float $annualInterestRate, int $loanTermYears): float
    {
        $monthlyInterestRate = ($annualInterestRate / 12) / 100;
        $numberOfMonths = $loanTermYears * 12;
        
        return ($loanAmount * $monthlyInterestRate) / 
               (1 - pow(1 + $monthlyInterestRate, -$numberOfMonths));
    }
}