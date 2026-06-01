<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MoneyValidation implements ValidationRule
{
    /**
     * Validate a Brazilian monetary format.
     * Accepts: 10, 10,00, 10.00, 1.000,00, 1000,00
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        // If it's already numeric (integer or float), it's valid
        if (is_numeric($value)) {
            if ((float) $value < 0) {
                $fail('O valor não pode ser negativo.');
            }
            return;
        }

        $normalized = preg_replace('/[R$\s]/', '', (string) $value);

        // Pattern for Brazilian currency: 1.000,00 or 1000,00 or 10,00 or 10
        if (! preg_match('/^\d{1,3}(?:\.\d{3})*(?:,\d{1,2})?$/', $normalized) &&
            ! preg_match('/^\d+(?:,\d{1,2})?$/', $normalized)) {
            $fail('Informe um valor monetário válido.');
        }
    }
}