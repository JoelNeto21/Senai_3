<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfOrCnpjValidation implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $digits = preg_replace('/\D/', '', (string) $value);
        $length = strlen($digits);

        if ($length === 11) {
            (new CpfValidation())->validate($attribute, $value, $fail);

            return;
        }

        if ($length === 14) {
            (new CnpjValidation())->validate($attribute, $value, $fail);

            return;
        }

        $fail('Informe um CPF (11 digitos) ou CNPJ (14 digitos) valido.');
    }
}
