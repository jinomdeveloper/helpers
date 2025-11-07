<?php

namespace Jinom\Helpers\Traits;

/**
 * HasCurrency Trait - Provides currency formatting methods.
 *
 * This trait provides methods for formatting numbers into Indonesian Rupiah (IDR)
 * currency format following Indonesian number formatting conventions.
 *
 * @author Rupadana <rupadanawayan@gmail.com>
 *
 * @version 1.0.0
 */
trait HasCurrency
{
    /**
     * Format amount to Indonesian Rupiah currency format.
     *
     * Converts a numeric value to Indonesian Rupiah format with proper
     * thousand separators using dots (.) as per Indonesian convention.
     * The amount is rounded to the nearest integer as IDR doesn't use decimals.
     *
     * @param  float|int  $amount  The amount to format
     * @return string Formatted currency string with "Rp " prefix
     *
     * @example
     * rupiah(1000000);    // Returns "Rp 1.000.000"
     * rupiah(150000);     // Returns "Rp 150.000"
     * rupiah(1500.75);    // Returns "Rp 1.501" (rounded)
     * rupiah(0);          // Returns "Rp 0"
     * rupiah(-50000);     // Returns "Rp -50.000"
     */
    public static function rupiah($amount)
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
    }

    /**
     * Convert number to Indonesian words (terbilang).
     *
     * Converts numeric values to their Indonesian text representation,
     * commonly used for checks, invoices, and official documents.
     *
     * @param  float|int  $amount  The number to convert to words
     * @return string Indonesian text representation with "rupiah" suffix
     *
     * @example
     * terbilang(1000000);     // Returns "Satu Juta Rupiah"
     * terbilang(150000);      // Returns "Seratus Lima Puluh Ribu Rupiah"
     * terbilang(25);          // Returns "Dua Puluh Lima Rupiah"
     * terbilang(1500);        // Returns "Seribu Lima Ratus Rupiah"
     */
    public static function terbilang($amount)
    {
        return trim(trim(self::_terbilang($amount)).' Rupiah');
    }

    /**
     * Lookup array for basic number words (0-11).
     * Defined as a static property to avoid recreating it on every recursive call.
     *
     * @var array<int, string>
     */
    private static $angka = [
        '',
        'Satu',
        'Dua',
        'Tiga',
        'Empat',
        'Lima',
        'Enam',
        'Tujuh',
        'Delapan',
        'Sembilan',
        'Sepuluh',
        'Sebelas',
    ];

    /**
     * Internal recursive method for number to words conversion.
     *
     * @param  float|int  $amount  The number to convert
     * @return string Text representation without "rupiah" suffix
     */
    private static function _terbilang($amount)
    {
        if ($amount < 12) {
            return self::$angka[$amount];
        }

        if ($amount < 20) {
            return self::_terbilang($amount - 10).' Belas';
        }

        if ($amount < 100) {
            $remainder = $amount % 10;

            return $remainder === 0
                ? self::_terbilang(intval($amount / 10)).' Puluh'
                : self::_terbilang(intval($amount / 10)).' Puluh '.self::_terbilang($remainder);
        }

        if ($amount < 200) {
            $remainder = $amount - 100;

            return $remainder === 0 ? 'Seratus' : 'Seratus '.self::_terbilang($remainder);
        }

        if ($amount < 1000) {
            $remainder = $amount % 100;

            return $remainder === 0
                ? self::_terbilang(intval($amount / 100)).' Ratus'
                : self::_terbilang(intval($amount / 100)).' Ratus '.self::_terbilang($remainder);
        }

        if ($amount < 2000) {
            $remainder = $amount - 1000;

            return $remainder === 0 ? 'Seribu' : 'Seribu '.self::_terbilang($remainder);
        }

        if ($amount < 1000000) {
            $remainder = $amount % 1000;

            return $remainder === 0
                ? self::_terbilang(intval($amount / 1000)).' Ribu'
                : self::_terbilang(intval($amount / 1000)).' Ribu '.self::_terbilang($remainder);
        }

        if ($amount < 1000000000) {
            $remainder = $amount % 1000000;

            return $remainder === 0
                ? self::_terbilang(intval($amount / 1000000)).' Juta'
                : self::_terbilang(intval($amount / 1000000)).' Juta '.self::_terbilang($remainder);
        }

        if ($amount < 1000000000000) {
            $remainder = fmod($amount, 1000000000);

            return $remainder === 0.0
                ? self::_terbilang($amount / 1000000000).' Miliar'
                : self::_terbilang($amount / 1000000000).' Miliar '.self::_terbilang($remainder);
        }

        if ($amount < 1000000000000000) {
            $remainder = fmod($amount, 1000000000000);

            return $remainder === 0.0
                ? self::_terbilang($amount / 1000000000000).' Triliun'
                : self::_terbilang($amount / 1000000000000).' Triliun '.self::_terbilang($remainder);
        }

        return '';
    }

    /**
     * Parse Indonesian currency strings back to numbers.
     *
     * Converts Indonesian formatted currency strings (with "Rp", dots as thousand
     * separators, commas as decimal separators) back to numeric values. Handles
     * negative amounts in parentheses or with minus signs.
     *
     * @param  string|int|float  $value  Currency string, number, or numeric value to parse
     * @return int|float Numeric value (int if no decimals, float if decimals present)
     *
     * @example
     * rupiah_to_number("Rp 1.000.000");      // Returns 1000000
     * rupiah_to_number("Rp 1.500,75");       // Returns 1500.75
     * rupiah_to_number("(Rp 50.000)");       // Returns -50000
     * rupiah_to_number("-Rp 25.000");        // Returns -25000
     * rupiah_to_number(150000);              // Returns 150000
     * rupiah_to_number("1.000.500");         // Returns 1000500
     */
    public static function rupiah_to_number($value)
    {
        // If already numeric, return as is
        if (is_int($value) || is_float($value)) {
            return $value;
        }

        $s = trim((string) $value);
        if ($s === '') {
            return 0;
        }

        // Detect negative: minus sign or parentheses, and clean in one pass
        $negative = str_contains($s, '-') || (str_contains($s, '(') && str_contains($s, ')'));

        // Remove all non-numeric characters except dots and commas in a single regex
        // This handles Rp, spaces, parentheses, minus signs, etc.
        $s = preg_replace('/[^\d\.,]/', '', $s);

        if ($s === '') {
            return 0;
        }

        // Indonesian format: dot as thousands separator, comma as decimal
        // Remove thousand separators, convert comma to decimal point
        $s = str_replace(['.', ','], ['', '.'], $s);

        // Early return for invalid or empty string
        if ($s === '' || ! is_numeric($s)) {
            return 0;
        }

        // Return int if no decimal part, otherwise float
        $num = str_contains($s, '.') ? (float) $s : (int) $s;

        return $negative ? -$num : $num;
    }
}
