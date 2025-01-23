<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\LoanCalculatorService;

class LoanCalculatorTest extends TestCase
{
    private $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new LoanCalculatorService();
    }

    public function test_monthly_payment_calculation()
    {
        $payment = $this->calculator->calculateMonthlyPayment(
            loanAmount: 300000,
            annualInterestRate: 5.5,
            loanTermYears: 30
        );

        $this->assertEquals(1703.37, round($payment, 2));
    }

    public function test_amortization_schedule_generation()
    {
        $result = $this->calculator->generateAmortizationSchedule(
            300000, // loan amount
            5.5,    // interest rate
            30      // years
        );

        $this->assertArrayHasKey('loan_details', $result);
        $this->assertArrayHasKey('schedule', $result);
        $this->assertEquals(360, count($result['schedule'])); // 30 years * 12 months
    }

    public function test_extra_payments_shorten_loan_term()
    {
        $extraPayments = [
            ['month' => 1, 'amount' => 500],
            ['month' => 2, 'amount' => 500]
        ];

        $result = $this->calculator->generateAmortizationSchedule(
            300000,
            5.5,
            30,
            $extraPayments
        );

        // Loan term should be shorter than 360 months due to extra payments
        $this->assertLessThan(360, count($result['schedule']));
    }
}