<?php

namespace Jinom\Helpers;

use Jinom\Helpers\Services\TaxService;
use Jinom\Helpers\Traits\HasCurrency;
use Jinom\Helpers\Traits\HasPhoneNumber;

/**
 * Jinom Helpers - Main helper class for Indonesian financial calculations.
 *
 * This class provides utility methods for tax calculations, currency formatting,
 * number-to-words conversion, and phone number formatting specifically designed
 * for Indonesian market with PPN (11%) tax rate, Indonesian Rupiah (IDR) currency
 * formatting, and Indonesian phone number conventions.
 *
 * @author Rupadana <rupadanawayan@gmail.com>
 *
 * @version 1.0.0
 */
class Helpers
{
    use HasCurrency;
    use HasPhoneNumber;

    /**
     * Create a new tax calculation service instance.
     *
     * This method creates a TaxService instance that can calculate Indonesian PPN (11%)
     * for both inclusive and exclusive tax scenarios.
     *
     * @param  float|int  $total  The total amount to calculate tax for
     * @param  bool  $includedTax  Whether the total already includes tax (default: true)
     *                             - true: Price includes tax, calculate base price and tax amount
     *                             - false: Price excludes tax, calculate tax amount and final price
     * @return TaxService Returns a TaxService instance with calculated values
     *
     * @example
     * Price includes tax (Rp 111,000 total)
     * $tax = Helpers::tax(111000, true);
     * echo $tax->basePrice;  // 100000
     * echo $tax->tax;        // 11000
     * echo $tax->taxedPrice; // 111000
     * @example
     * Price excludes tax (Rp 100,000 base)
     * $tax = Helpers::tax(100000, false);
     * echo $tax->basePrice;  // 100000
     * echo $tax->tax;        // 11000
     * echo $tax->taxedPrice; // 111000
     */
    public static function tax($total, $includedTax = true)
    {
        return new TaxService($total, $includedTax);
    }
}
