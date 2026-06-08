<?php

namespace App\Support;

class BrazilianFormat
{
    public static function onlyDigits(mixed $value): string
    {
        return preg_replace('/\D/', '', (string) $value) ?? '';
    }

    public static function cpf(mixed $value): string
    {
        $digits = self::onlyDigits($value);

        if (strlen($digits) !== 11) {
            return (string) $value;
        }

        return substr($digits, 0, 3) . '.' .
            substr($digits, 3, 3) . '.' .
            substr($digits, 6, 3) . '-' .
            substr($digits, 9, 2);
    }

    public static function cpfCnpj(mixed $value): string
    {
        $digits = self::onlyDigits($value);

        if (strlen($digits) === 11) {
            return self::cpf($digits);
        }

        if (strlen($digits) !== 14) {
            return (string) $value;
        }

        return substr($digits, 0, 2) . '.' .
            substr($digits, 2, 3) . '.' .
            substr($digits, 5, 3) . '/' .
            substr($digits, 8, 4) . '-' .
            substr($digits, 12, 2);
    }

    public static function phone(mixed $value): string
    {
        $digits = self::onlyDigits($value);

        if (strlen($digits) === 10) {
            return '(' . substr($digits, 0, 2) . ') ' .
                substr($digits, 2, 4) . '-' .
                substr($digits, 6, 4);
        }

        if (strlen($digits) === 11) {
            return '(' . substr($digits, 0, 2) . ') ' .
                substr($digits, 2, 5) . '-' .
                substr($digits, 7, 4);
        }

        return (string) $value;
    }

    public static function currency(mixed $value): string
    {
        return 'R$ ' . number_format(self::decimal($value), 2, ',', '.');
    }

    public static function currencyInput(mixed $value): string
    {
        return number_format(self::decimal($value), 2, ',', '.');
    }

    public static function decimal(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $normalized = str_replace(['R$', ' '], '', (string) $value);
        $normalized = str_replace('.', '', $normalized);
        $normalized = str_replace(',', '.', $normalized);

        return is_numeric($normalized) ? (float) $normalized : 0.0;
    }

    public static function integer(mixed $value): int
    {
        return (int) self::onlyDigits($value);
    }
}
