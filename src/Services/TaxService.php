<?php

namespace Jinom\Helpers\Services;

/**
 * Tax Service - Indonesian PPN (11%) tax calculation service.
 *
 * This service handles tax calculations for Indonesian market with support for
 * both inclusive and exclusive tax scenarios. The default tax rate is set to 11%
 * which corresponds to the Indonesian PPN (Pajak Pertambahan Nilai).
 *
 * @author Rupadana <rupadanawayan@gmail.com>
 *
 * @version 1.0.0
 */
class TaxService
{
    /**
     * The tax rate percentage (Indonesian PPN).
     *
     * @var float
     */
    protected $taxRate = 11;

    /**
     * The base price before tax is applied.
     *
     * @var float
     */
    public $basePrice;

    /**
     * The calculated tax amount.
     *
     * @var float
     */
    public $tax;

    /**
     * The final price including tax.
     *
     * @var float
     */
    public $taxedPrice;

    /**
     * Constructor - Initialize tax calculation.
     *
     * @param  float|int  $total  The total amount to calculate tax for
     * @param  bool  $includedTax  Whether the total already includes tax
     */
    public function __construct(float|int $total, $includedTax = true)
    {
        if ($includedTax) {
            $this->taxedPrice = (float) $total;
            $this->basePrice = $this->getOriginalPriceFromInclusive($total);
            $this->tax = $this->taxedPrice - $this->basePrice;
        } else {
            $this->basePrice = (float) $total;
            $this->tax = $this->calculateTax($total);
            $this->taxedPrice = $this->basePrice + $this->tax;
        }
    }

    /**
     * Calculate tax amount from base price (exclusive tax calculation).
     *
     * This method calculates the tax amount that should be added to a base price
     * to get the final price including tax.
     *
     * @param  float  $totalPrice  The base price before tax
     * @return float The calculated tax amount (rounded to nearest integer)
     *
     * @example
     * calculateTax(100000); // Returns 11000
     */
    public function calculateTax(float $totalPrice): float
    {
        return round($totalPrice * ($this->taxRate / 100));
    }

    /**
     * Calculate original price from tax-inclusive total (inclusive tax calculation).
     *
     * This method extracts the base price from a total that already includes tax.
     * Useful when you know the final price customers pay and need to determine
     * the original price before tax was added.
     *
     * @param  float  $totalPrice  Total price that includes tax
     * @return float The original price before tax was applied (rounded to nearest integer)
     *
     * @example
     * getOriginalPriceFromInclusive(111000); // Returns 100000
     *
     * Formula: originalPrice = totalPrice / (1 + (taxRate / 100))
     */
    public function getOriginalPriceFromInclusive(float $totalPrice): float
    {
        return round($totalPrice / (1 + ($this->taxRate / 100)));
    }
}
