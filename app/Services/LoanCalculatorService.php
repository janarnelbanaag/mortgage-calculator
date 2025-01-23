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

    private function calculateEffectiveInterestRate(
        float $principal,
        float $totalInterest,
        int $actualMonths
    ): float {
        $remainingBalance = $loanAmount;
        $totalActualInterestPaid = 0;
        $totalActualPrincipalPaid = 0;

        foreach ($amortizationSchedule as $payment) {
            $monthlyInterestRate = ($annualInterestRate / 12) / 100;
            $monthlyInterest = $remainingBalance * $monthlyInterestRate;
            $monthlyPrincipal = $payment['principal_payment'];
            $extraPayment = $payment['extra_payment'] ?? 0;

            $totalActualInterestPaid += $monthlyInterest;
            $totalActualPrincipalPaid += $monthlyPrincipal + $extraPayment;

            $remainingBalance -= ($monthlyPrincipal + $extraPayment);

            if ($remainingBalance <= 0) {
                break;
            }
        }

        $effectiveRate = ($totalActualInterestPaid / $loanAmount) * 
                         (($loanAmount / $totalActualPrincipalPaid) * 100);

        return round($effectiveRate, 2);
    }

    public function generateAmortizationSchedule(
        float $loanAmount,
        float $annualInterestRate,
        int $loanTermYears,
        array $extraPayments = []
    ): array {
        $monthlyPayment = $this->calculateMonthlyPayment($loanAmount, $annualInterestRate, $loanTermYears);
        $monthlyInterestRate = ($annualInterestRate / 12) / 100;
        $schedule = [];
        $balance = $loanAmount;
        $totalInterestPaid = 0;
        
        for ($month = 1; $month <= ($loanTermYears * 12); $month++) {
            $interestPayment = $balance * $monthlyInterestRate;
            $principalPayment = $monthlyPayment - $interestPayment;
            
            $extraPayment = 0;
            foreach ($extraPayments as $payment) {
                if ($payment['month'] == $month) {
                    $extraPayment = $payment['amount'];
                    $principalPayment += $extraPayment;
                }
            }
            
            if ($principalPayment > $balance) {
                $principalPayment = $balance;
                $monthlyPayment = $principalPayment + $interestPayment;
            }
            
            $endingBalance = $balance - $principalPayment;
            $totalInterestPaid += $interestPayment;
            
            $schedule[] = [
                'month' => $month,
                'starting_balance' => $balance,
                'monthly_payment' => $monthlyPayment,
                'principal_payment' => $principalPayment,
                'interest_payment' => $interestPayment,
                'extra_payment' => $extraPayment,
                'ending_balance' => $endingBalance
            ];
            
            $balance = $endingBalance;
            
            // Break if loan is paid off
            if ($balance <= 0) {
                break;
            }
        }
        
        $effectiveRate = $this->calculateEffectiveInterestRate(
            $loanAmount,
            $totalInterestPaid,
            count($schedule)
        );
        
        return [
            'loan_details' => [
                'loan_amount' => $loanAmount,
                'annual_interest_rate' => $annualInterestRate,
                'loan_term_years' => $loanTermYears,
                'monthly_payment' => $monthlyPayment,
                'effective_interest_rate' => $effectiveRate,
                'total_interest_paid' => $totalInterestPaid,
                'actual_loan_term_months' => count($schedule)
            ],
            'schedule' => $schedule
        ];
    }
}