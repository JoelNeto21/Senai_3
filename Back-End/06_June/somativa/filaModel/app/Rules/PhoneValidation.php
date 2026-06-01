<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phone = preg_replace('/\D/', '', (string) $value);
        $len = strlen($phone);

        if ($len < 10 || $len > 11) {
            $fail('Informe um telefone válido com 10 ou 11 dígitos.');
            return;
        }

        // Check for valid DDD (11-99)
        $ddd = (int) substr($phone, 0, 2);
        if ($ddd < 11 || $ddd > 99) {
            $fail('Informe um DDD válido.');
            return;
        }

        // For 11 digits, second digit must be 9 (mobile)
        if ($len === 11 && $phone[2] !== '9') {
            $fail('Informe um número de telefone válido.');
        }
    }
}