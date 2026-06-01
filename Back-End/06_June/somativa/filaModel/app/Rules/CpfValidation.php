<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = preg_replace('/\D/', '', (string) $value);

        if (strlen($cpf) !== 11) {
            $fail('O CPF deve conter 11 dígitos.');
            return;
        }

        // Reject all identical digits
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            $fail('Informe um CPF válido.');
            return;
        }

        // Validate first check digit
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $cpf[$i] * (10 - $i);
        }
        $remainder = ($sum * 10) % 11;
        $digit1 = $remainder == 10 ? 0 : $remainder;

        if ((int) $cpf[9] !== $digit1) {
            $fail('Informe um CPF válido.');
            return;
        }

        // Validate second check digit
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += (int) $cpf[$i] * (11 - $i);
        }
        $remainder = ($sum * 10) % 11;
        $digit2 = $remainder == 10 ? 0 : $remainder;

        if ((int) $cpf[10] !== $digit2) {
            $fail('Informe um CPF válido.');
        }
    }
}