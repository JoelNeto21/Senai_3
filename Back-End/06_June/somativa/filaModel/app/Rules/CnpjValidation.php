<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CnpjValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cnpj = preg_replace('/\D/', '', (string) $value);

        if (strlen($cnpj) !== 14) {
            $fail('O CNPJ deve conter 14 dígitos.');
            return;
        }

        // Reject all identical digits
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            $fail('Informe um CNPJ válido.');
            return;
        }

        // Validate first check digit
        $weights1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $cnpj[$i] * $weights1[$i];
        }
        $remainder = $sum % 11;
        $digit1 = $remainder < 2 ? 0 : 11 - $remainder;

        if ((int) $cnpj[12] !== $digit1) {
            $fail('Informe um CNPJ válido.');
            return;
        }

        // Validate second check digit
        $weights2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += (int) $cnpj[$i] * $weights2[$i];
        }
        $remainder = $sum % 11;
        $digit2 = $remainder < 2 ? 0 : 11 - $remainder;

        if ((int) $cnpj[13] !== $digit2) {
            $fail('Informe um CNPJ válido.');
        }
    }
}