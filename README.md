# Jinom Helpers

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jinomdeveloper/helpers.svg?style=flat-square)](https://packagist.org/packages/jinomdeveloper/helpers)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jinomdeveloper/helpers/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jinomdeveloper/helpers/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jinomdeveloper/helpers/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jinomdeveloper/helpers/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jinomdeveloper/helpers.svg?style=flat-square)](https://packagist.org/packages/jinomdeveloper/helpers)

A Laravel package that provides useful utility functions for Indonesian developers. This package includes tax calculation services and Indonesian Rupiah currency formatting helpers, making it easier to handle financial calculations in Indonesian web applications.

Perfect for e-commerce applications, invoicing systems, or any Laravel project that needs to handle Indonesian tax calculations and currency formatting.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/helpers.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/helpers)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Features

- **Tax Calculator**: Calculate Indonesian tax (PPN 11%) with support for both inclusive and exclusive tax calculations
- **Currency Formatter**: Format numbers to Indonesian Rupiah (IDR) currency format
- **Number to Words**: Convert numbers to Indonesian text (terbilang)
- **Currency Parser**: Parse Indonesian currency strings back to numbers
- **Phone Number Formatter**: Format Indonesian phone numbers to E.164 international format
- **Easy Integration**: Simple Laravel facade and service provider integration
- **Clean API**: Intuitive and chainable methods for common operations

## Installation

You can install the package via composer:

```bash
composer require jinomdeveloper/helpers
```

The package will automatically register itself via Laravel's package discovery feature.

## Usage

### Tax Calculations

The package provides a comprehensive tax calculation service that handles Indonesian PPN (11% tax rate).

#### Tax Inclusive Calculations (Price includes tax)

When you have a price that already includes tax and want to break it down:

```php
use Jinom\Helpers\Facades\Helpers;

// Price includes tax (e.g., customer sees Rp 111,000)
$taxService = Helpers::tax(111000, true);

echo $taxService->basePrice;    // 100000 (original price before tax)
echo $taxService->tax;          // 11000 (tax amount)
echo $taxService->taxedPrice;   // 111000 (total price with tax)
```

#### Tax Exclusive Calculations (Price excludes tax)

When you have a base price and want to add tax:

```php
use Jinom\Helpers\Facades\Helpers;

// Base price without tax
$taxService = Helpers::tax(100000, false);

echo $taxService->basePrice;    // 100000 (original price)
echo $taxService->tax;          // 11000 (calculated tax)
echo $taxService->taxedPrice;   // 111000 (total price with tax)
```

### Currency Formatting

Format numbers to Indonesian Rupiah currency format:

```php
use Jinom\Helpers\Facades\Helpers;

echo Helpers::rupiah(1000000);     // "Rp 1.000.000"
echo Helpers::rupiah(150000);      // "Rp 150.000"
echo Helpers::rupiah(1500.75);     // "Rp 1.501" (rounded to nearest integer)
```

### Number to Words (Terbilang)

Convert numbers to Indonesian words:

```php
use Jinom\Helpers\Facades\Helpers;

echo Helpers::terbilang(1000000);  // "Satu Juta Rupiah"
echo Helpers::terbilang(150000);   // "Seratus Lima Puluh Ribu Rupiah"
echo Helpers::terbilang(25);       // "Dua Puluh Lima Rupiah"
```

### Currency String to Number

Parse Indonesian currency strings back to numbers:

```php
use Jinom\Helpers\Facades\Helpers;

echo Helpers::rupiah_to_number("Rp 1.000.000");    // 1000000
echo Helpers::rupiah_to_number("Rp 150.000");      // 150000
echo Helpers::rupiah_to_number("1.500,75");        // 1500.75
echo Helpers::rupiah_to_number("(Rp 50.000)");     // -50000 (negative)
```

### Phone Number Formatting

Format Indonesian phone numbers to E.164 international format:

```php
use Jinom\Helpers\Facades\Helpers;

echo Helpers::to_e164("081234567890");      // "+6281234567890"
echo Helpers::to_e164("0812-3456-7890");    // "+6281234567890"
echo Helpers::to_e164("62812345678");       // "+62812345678"
echo Helpers::to_e164("+6281234567890");    // "+6281234567890"

// With custom country code
echo Helpers::to_e164("081234567890", "1"); // "+181234567890"
```

### Using Without Facade

You can also use the classes directly:

```php
use Jinom\Helpers\Helpers;
use Jinom\Helpers\Services\TaxService;

// Direct tax calculation
$taxService = new TaxService(111000, true);

// Using the main class
$helpers = new Helpers();
$taxService = $helpers::tax(111000, true);

// Currency formatting
echo $helpers::rupiah(1000000);
```

### Practical Examples

#### E-commerce Product Pricing

```php
use Jinom\Helpers\Facades\Helpers;

// Product price displayed to customer (includes tax)
$displayPrice = 555000;

$tax = Helpers::tax($displayPrice, true);

echo "Product Price: " . Helpers::rupiah($tax->basePrice);     // "Rp 500.000"
echo "Tax (PPN 11%): " . Helpers::rupiah($tax->tax);          // "Rp 55.000"
echo "Total Price: " . Helpers::rupiah($tax->taxedPrice);     // "Rp 555.000"
echo "Amount in Words: " . Helpers::terbilang($tax->taxedPrice); // "Lima Ratus Lima Puluh Lima Ribu rupiah"
```

#### Customer Form Processing

```php
use Jinom\Helpers\Facades\Helpers;

// Customer input from form
$customerInput = [
    'amount' => 'Rp 1.500.000',
    'phone' => '0812-3456-7890'
];

// Parse and process
$amount = Helpers::rupiah_to_number($customerInput['amount']);  // 1500000
$phone = Helpers::to_e164($customerInput['phone']);           // "+6281234567890"

// Calculate with tax
$tax = Helpers::tax($amount, false);
echo "Final amount: " . Helpers::rupiah($tax->taxedPrice);    // "Rp 1.665.000"
echo "Amount in words: " . Helpers::terbilang($tax->taxedPrice);
echo "Contact: " . $phone;
```

#### Invoice Generation

```php
use Jinom\Helpers\Facades\Helpers;

$items = [
    ['name' => 'Product A', 'price' => 100000],
    ['name' => 'Product B', 'price' => 250000],
];

$subtotal = 0;
$totalTax = 0;

foreach ($items as $item) {
    $tax = Helpers::tax($item['price'], false);
    
    echo $item['name'] . ": " . Helpers::rupiah($tax->basePrice) . "\n";
    
    $subtotal += $tax->basePrice;
    $totalTax += $tax->tax;
}

echo "Subtotal: " . Helpers::rupiah($subtotal) . "\n";        // "Rp 350.000"
echo "Tax (PPN 11%): " . Helpers::rupiah($totalTax) . "\n";   // "Rp 38.500"
echo "Total: " . Helpers::rupiah($subtotal + $totalTax) . "\n"; // "Rp 388.500"
```

## API Reference

### Helpers Class

The main entry point for all helper functions.

#### Methods

##### `tax(float|int $total, bool $includedTax = true): TaxService`

Creates a tax calculation service for Indonesian PPN (11%).

**Parameters:**
- `$total` - The amount to calculate tax for
- `$includedTax` - Whether the total includes tax (default: `true`)

**Returns:** `TaxService` instance with calculated values

##### `rupiah(float|int $amount): string`

Formats a number as Indonesian Rupiah currency.

**Parameters:**
- `$amount` - The amount to format

**Returns:** Formatted currency string with "Rp " prefix

##### `terbilang(float|int $amount): string`

Converts a number to Indonesian words (terbilang).

**Parameters:**
- `$amount` - The number to convert to words

**Returns:** Indonesian text representation with "rupiah" suffix

##### `rupiah_to_number(string|int|float $value): int|float`

Parses Indonesian currency strings back to numbers.

**Parameters:**
- `$value` - Currency string, number, or numeric value to parse

**Returns:** Numeric value (int or float based on decimal presence)

##### `to_e164(string $phone, string $countryCode = '62'): string`

Formats phone numbers to E.164 international format.

**Parameters:**
- `$phone` - Phone number to format
- `$countryCode` - Country code (default: '62' for Indonesia)

**Returns:** E.164 formatted phone number with "+" prefix

### TaxService Class

Handles tax calculations with the following public properties:

- `$basePrice` - The original price before tax
- `$tax` - The calculated tax amount  
- `$taxedPrice` - The final price including tax

### Facade Usage

```php
use Jinom\Helpers\Facades\Helpers;

// All methods available statically
$taxService = Helpers::tax(100000, false);
$formatted = Helpers::rupiah(100000);
```

## Configuration

The package uses a fixed 11% tax rate for Indonesian PPN. This rate is hardcoded in the `TaxService` class and reflects the current Indonesian tax law.

## Error Handling

The package handles common edge cases:

- **Negative amounts**: Supported for refunds and adjustments
- **Decimal precision**: Amounts are rounded to nearest integer for IDR
- **Zero amounts**: Properly handled in both tax and currency formatting
- **Large numbers**: No arbitrary limits on calculation amounts

## Performance Considerations

- Tax calculations use simple arithmetic operations (very fast)
- Currency formatting uses PHP's built-in `number_format()` function
- No external API calls or database queries
- Suitable for high-traffic applications

## Compatibility

- **PHP**: 8.4+
- **Laravel**: 10.x, 11.x, 12.x
- **Framework**: Laravel package, but core classes can work standalone

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Rupadana](https://github.com/rupadana)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
