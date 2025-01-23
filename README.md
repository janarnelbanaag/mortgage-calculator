# Mortgage Loan Calculator

A Laravel-based mortgage loan calculator that generates amortization schedules with support for extra payments.

## Features

- Calculate monthly mortgage payments
- Generate detailed amortization schedules
- Support for extra payments
- Calculate effective interest rates
- Store calculation history in database

## Requirements

- PHP 8.1 or higher
- Composer
- (Optional) RDMS - MySQL, etc just edit .env file
- Laravel 10.x

## Installation

1. Clone the repository
```bash
git clone https://github.com/janarnelbanaag/mortgage-calculator.git
cd mortgage-calculator
```

2. Run migrations
```bash
php artisan migrate
```

## Running Tests

```bash
php artisan test
```

## API Usage

Send a POST request to `/api/calculate-loan` with the following parameters:

```json
{
    "loan_amount": 300000,
    "annual_interest_rate": 5.5,
    "loan_term_years": 30,
    "extra_payments": [
        {
            "month": 1,
            "amount": 500
        }
    ]
}
```

## Development Process

1. Created initial Laravel project
2. Implemented loan calculation service
3. Added database migrations
4. Created API endpoints
5. Added input validation
6. Implemented unit tests
7. Added support for extra payments
8. Calculated effective interest rates
