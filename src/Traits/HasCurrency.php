<?php

namespace Jinom\Helpers\Traits;

/**
 * HasCurrency Trait - Provides currency formatting methods.
 * 
 * This trait provides methods for formatting numbers into Indonesian Rupiah (IDR)
 * currency format following Indonesian number formatting conventions.
 * 
 * @package Jinom\Helpers\Traits
 * @author Rupadana <rupadanawayan@gmail.com>
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
     * @param float|int $amount The amount to format
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
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Convert number to Indonesian words (terbilang).
     * 
     * Converts numeric values to their Indonesian text representation,
     * commonly used for checks, invoices, and official documents.
     * 
     * @param float|int $amount The number to convert to words
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
        return trim(trim(self::_terbilang($amount)) . ' Rupiah');
    }

    /**
     * Internal recursive method for number to words conversion.
     * 
     * @param float|int $amount The number to convert
     * @return string Text representation without "rupiah" suffix
     */
    private static function _terbilang($amount)
    {
        $angka = array(
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
            'Sebelas'
        );

        if ($amount < 12) {
            return $angka[$amount];
        } elseif ($amount < 20) {
            return self::_terbilang($amount - 10) . ' Belas';
        } elseif ($amount < 100) {
            $puluh = self::_terbilang(intval($amount / 10));
            $satuan = self::_terbilang($amount % 10);
            $result = $puluh . ' Puluh';
            if (!empty($satuan)) {
                $result .= ' ' . $satuan;
            }
            return trim($result);
        } elseif ($amount < 200) {
            $sisa = self::_terbilang($amount - 100);
            $result = 'Seratus';
            if (!empty($sisa)) {
                $result .= ' ' . $sisa;
            }
            return trim($result);
        } elseif ($amount < 1000) {
            $ratus = self::_terbilang(intval($amount / 100));
            $sisa = self::_terbilang($amount % 100);
            $result = $ratus . ' Ratus';
            if (!empty($sisa)) {
                $result .= ' ' . $sisa;
            }
            return trim($result);
        } elseif ($amount < 2000) {
            $sisa = self::_terbilang($amount - 1000);
            $result = 'Seribu';
            if (!empty($sisa)) {
                $result .= ' ' . $sisa;
            }
            return trim($result);
        } elseif ($amount < 1000000) {
            $ribu = self::_terbilang(intval($amount / 1000));
            $sisa = self::_terbilang($amount % 1000);
            $result = $ribu . ' Ribu';
            if (!empty($sisa)) {
                $result .= ' ' . $sisa;
            }
            return trim($result);
        } elseif ($amount < 1000000000) {
            $juta = self::_terbilang(intval($amount / 1000000));
            $sisa = self::_terbilang($amount % 1000000);
            $result = $juta . ' Juta';
            if (!empty($sisa)) {
                $result .= ' ' . $sisa;
            }
            return trim($result);
        } elseif ($amount < 1000000000000) {
            return self::_terbilang($amount / 1000000000) . ' Miliar' . self::_terbilang(fmod($amount, 1000000000));
        } elseif ($amount < 1000000000000000) {
            return self::_terbilang($amount / 1000000000000) . ' Triliun' . self::_terbilang(fmod($amount, 1000000000000));
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
     * @param string|int|float $value Currency string, number, or numeric value to parse
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
        // Jika sudah numerik, kembalikan langsung (cast ke int/float)
        if (is_int($value) || is_float($value)) {
            return $value;
        }

        $s = trim((string) $value);
        if ($s === '') {
            return 0;
        }

        // Deteksi negatif: tanda minus atau tanda kurung
        $negative = false;
        if (strpos($s, '(') !== false && strpos($s, ')') !== false) {
            $negative = true;
            $s = preg_replace('/[()]/', '', $s);
        }
        if (strpos($s, '-') !== false) {
            $negative = true;
            $s = str_replace('-', '', $s);
        }

        // Hapus simbol "Rp" (besar/kecil), spasi, dan karakter bukan numerik kecuali '.' dan ','
        $s = preg_replace('/[Rr][Pp]\.?/u', '', $s);
        $s = preg_replace('/[^\d\.,]/u', '', $s);

        if ($s === '') {
            return 0;
        }

        // Asumsi format Indonesia: titik sebagai pemisah ribuan, koma sebagai desimal
        // Hapus titik ribuan, ubah koma menjadi titik desimal
        $s = str_replace('.', '', $s);
        $s = str_replace(',', '.', $s);

        // Jika masih kosong atau bukan angka, kembalikan 0
        if ($s === '' || !is_numeric($s)) {
            return 0;
        }

        // Kembalikan int jika tidak ada bagian desimal, sebaliknya float
        $num = (strpos($s, '.') !== false) ? (float) $s : (int) $s;

        return $negative ? -$num : $num;
    }
}
