<?php

namespace Jinom\Helpers\Traits;

/**
 * HasPhoneNumber Trait - Provides phone number formatting methods.
 *
 * This trait provides methods for formatting and validating phone numbers
 * according to Indonesian phone number conventions.
 *
 * @author Rupadana <rupadanawayan@gmail.com>
 *
 * @version 1.0.0
 */
trait HasPhoneNumber
{
    /**
     * Format phone number to E.164 international format.
     *
     * Converts Indonesian phone numbers to the E.164 international standard format.
     * Handles various input formats including local numbers starting with 0,
     * numbers with country code, and already formatted international numbers.
     *
     * @param  string  $phone  Phone number to format (various formats accepted)
     * @param  string  $countryCode  Country code without + (default: '62' for Indonesia)
     * @return string E.164 formatted phone number with "+" prefix, or empty string if invalid
     *
     * @example
     * to_e164("081234567890");        // Returns "+6281234567890"
     * to_e164("0812-3456-7890");      // Returns "+6281234567890"
     * to_e164("62812345678");         // Returns "+62812345678"
     * to_e164("+6281234567890");      // Returns "+6281234567890"
     * to_e164("81234567890");         // Returns "+6281234567890"
     * to_e164("081234567890", "1");   // Returns "+181234567890" (US format)
     * to_e164("");                    // Returns ""
     */
    public static function to_e164($phone, $countryCode = '62')
    {
        if (empty($phone)) {
            return '';
        }

        // Hapus semua karakter non-numerik kecuali tanda +
        $cleaned = preg_replace('/[^\d+]/', '', (string) $phone);

        if (empty($cleaned)) {
            return '';
        }

        // Jika sudah dimulai dengan +, kembalikan langsung
        if (strpos($cleaned, '+') === 0) {
            return $cleaned;
        }

        // Hapus tanda + jika ada di tengah
        $cleaned = str_replace('+', '', $cleaned);

        // Jika dimulai dengan 0, ganti dengan kode negara
        if (strpos($cleaned, '0') === 0) {
            $cleaned = $countryCode.substr($cleaned, 1);
        }
        // Jika dimulai dengan kode negara tanpa +, tambahkan +
        elseif (strpos($cleaned, $countryCode) === 0) {
            // Sudah benar, tinggal tambah +
        }
        // Jika tidak dimulai dengan 0 atau kode negara, anggap nomor lokal
        else {
            $cleaned = $countryCode.$cleaned;
        }

        return '+'.$cleaned;
    }
}
