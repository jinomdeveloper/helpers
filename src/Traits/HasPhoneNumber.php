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

        // Remove all non-numeric characters except + in a single pass
        $cleaned = preg_replace('/[^\d+]/', '', (string) $phone);

        // Handle empty result after cleaning
        if ($cleaned === '' || $cleaned === null) {
            return '';
        }

        // If already starts with +, return as is
        if (str_starts_with($cleaned, '+')) {
            return $cleaned;
        }

        // Remove any + in the middle
        $cleaned = str_replace('+', '', $cleaned);

        // Handle different formats efficiently
        if (str_starts_with($cleaned, '0')) {
            // Local number starting with 0: replace with country code
            return '+'.$countryCode.substr($cleaned, 1);
        }

        // Check if it already starts with country code
        if (str_starts_with($cleaned, $countryCode)) {
            return '+'.$cleaned;
        }

        // Assume local number without 0 prefix
        return '+'.$countryCode.$cleaned;
    }
}
