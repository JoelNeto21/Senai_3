<?php

namespace App\Rules;

use App\Support\BrazilianFormat;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PositiveMoneyValidation implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (BrazilianFormat::decimal($value) <= 0) {
            $fail('Informe um valor maior que zero.');
        }
    }
}
