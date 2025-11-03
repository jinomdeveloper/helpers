<?php

namespace Jinom\Helpers\Facades;

use Illuminate\Support\Facades\Facade;
use Jinom\Helpers\Services\TaxService;

/**
 * Helpers Facade - Laravel Facade for Jinom Helpers.
 * 
 * This facade provides static access to the Jinom Helpers functionality
 * including tax calculations and currency formatting methods.
 * 
 * @package Jinom\Helpers\Facades
 * @author Rupadana <rupadanawayan@gmail.com>
 * @version 1.0.0
 * 
 * @method static TaxService tax(float|int $total, bool $includedTax = true) Create tax calculation service
 * @method static string rupiah(float|int $amount) Format amount to Indonesian Rupiah
 * @method static string terbilang(float|int $amount) Convert number to Indonesian words
 * @method static int|float rupiah_to_number(string|int|float $value) Parse currency string to number
 * @method static string to_e164(string $phone, string $countryCode = '62') Format phone number to E.164
 * 
 * @see \Jinom\Helpers\Helpers
 */
class Helpers extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string The facade accessor for the Helpers class
     */
    protected static function getFacadeAccessor(): string
    {
        return \Jinom\Helpers\Helpers::class;
    }
}
