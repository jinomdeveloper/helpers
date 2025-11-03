<?php

/**
 * Jinom Helpers - Usage Examples
 *
 * This file demonstrates various usage scenarios for the Jinom Helpers package.
 * Run this file to see the package in action.
 */

require_once __DIR__.'/vendor/autoload.php';

use Jinom\Helpers\Helpers;

echo "=== Jinom Helpers Examples ===\n\n";

// Example 1: Tax calculation with inclusive price
echo "Example 1: Price includes tax (Rp 111,000)\n";
echo "----------------------------------------\n";
$inclusiveTax = Helpers::tax(111000, true);
echo 'Total price (with tax): '.Helpers::rupiah($inclusiveTax->taxedPrice)."\n";
echo 'Base price (before tax): '.Helpers::rupiah($inclusiveTax->basePrice)."\n";
echo 'Tax amount (PPN 11%): '.Helpers::rupiah($inclusiveTax->tax)."\n\n";

// Example 2: Tax calculation with exclusive price
echo "Example 2: Price excludes tax (Rp 100,000)\n";
echo "------------------------------------------\n";
$exclusiveTax = Helpers::tax(100000, false);
echo 'Base price (before tax): '.Helpers::rupiah($exclusiveTax->basePrice)."\n";
echo 'Tax amount (PPN 11%): '.Helpers::rupiah($exclusiveTax->tax)."\n";
echo 'Total price (with tax): '.Helpers::rupiah($exclusiveTax->taxedPrice)."\n\n";

// Example 3: E-commerce product pricing
echo "Example 3: E-commerce Product Pricing\n";
echo "------------------------------------\n";
$productPrice = 555000; // Customer sees this price
$product = Helpers::tax($productPrice, true);

echo "Product Information:\n";
echo '- Display Price: '.Helpers::rupiah($product->taxedPrice)."\n";
echo '- Base Price: '.Helpers::rupiah($product->basePrice)."\n";
echo '- Tax (PPN 11%): '.Helpers::rupiah($product->tax)."\n\n";

// Example 4: Invoice calculation
echo "Example 4: Invoice with Multiple Items\n";
echo "------------------------------------\n";
$items = [
    ['name' => 'Laptop', 'price' => 5000000],
    ['name' => 'Mouse', 'price' => 150000],
    ['name' => 'Keyboard', 'price' => 300000],
];

$subtotal = 0;
$totalTax = 0;

echo "Invoice Details:\n";
foreach ($items as $item) {
    $itemTax = Helpers::tax($item['price'], false); // Prices exclude tax
    echo "- {$item['name']}: ".Helpers::rupiah($itemTax->basePrice).
         ' (+ '.Helpers::rupiah($itemTax->tax)." tax)\n";

    $subtotal += $itemTax->basePrice;
    $totalTax += $itemTax->tax;
}

echo "\nInvoice Summary:\n";
echo '- Subtotal: '.Helpers::rupiah($subtotal)."\n";
echo '- Tax (PPN 11%): '.Helpers::rupiah($totalTax)."\n";
echo '- Total Amount: '.Helpers::rupiah($subtotal + $totalTax)."\n\n";

// Example 5: Currency formatting
echo "Example 5: Currency Formatting\n";
echo "-----------------------------\n";
$amounts = [1000000, 150000, 1500.75, 0, -50000];

foreach ($amounts as $amount) {
    echo "Amount {$amount} -> ".Helpers::rupiah($amount)."\n";
}

echo "\n";

// Example 6: Number to words (Terbilang)
echo "Example 6: Number to Words (Terbilang)\n";
echo "-------------------------------------\n";
$numbersToConvert = [25, 1500, 150000, 1000000, 555000];

foreach ($numbersToConvert as $number) {
    echo Helpers::rupiah($number).' -> '.Helpers::terbilang($number)."\n";
}

echo "\n";

// Example 7: Currency parsing
echo "Example 7: Currency String Parsing\n";
echo "---------------------------------\n";
$currencyStrings = [
    'Rp 1.000.000',
    'Rp 1.500,75',
    '(Rp 50.000)',
    '-Rp 25.000',
    '1.000.500',
];

foreach ($currencyStrings as $currencyString) {
    $parsed = Helpers::rupiah_to_number($currencyString);
    echo "'{$currencyString}' -> {$parsed}\n";
}

echo "\n";

// Example 8: Phone number formatting
echo "Example 8: Phone Number Formatting\n";
echo "---------------------------------\n";
$phoneNumbers = [
    '081234567890',
    '0812-3456-7890',
    '62812345678',
    '+6281234567890',
    '81234567890',
];

foreach ($phoneNumbers as $phoneNumber) {
    $formatted = Helpers::to_e164($phoneNumber);
    echo "'{$phoneNumber}' -> '{$formatted}'\n";
}

echo "\n";

// Example 9: Complete workflow
echo "Example 9: Complete Customer Order Workflow\n";
echo "==========================================\n";

// Customer submits form
$customerOrder = [
    'customer_name' => 'John Doe',
    'customer_phone' => '0812-3456-7890',
    'product_price' => 'Rp 2.500.000',
    'include_tax' => false,
];

echo "Customer Order Processing:\n";
echo "- Customer: {$customerOrder['customer_name']}\n";

// Parse and format phone
$formattedPhone = Helpers::to_e164($customerOrder['customer_phone']);
echo "- Phone: {$customerOrder['customer_phone']} -> {$formattedPhone}\n";

// Parse price
$basePrice = Helpers::rupiah_to_number($customerOrder['product_price']);
echo "- Requested Price: {$customerOrder['product_price']} -> ".Helpers::rupiah($basePrice)."\n";

// Calculate tax
$tax = Helpers::tax($basePrice, $customerOrder['include_tax']);
echo '- Base Price: '.Helpers::rupiah($tax->basePrice)."\n";
echo '- Tax (PPN 11%): '.Helpers::rupiah($tax->tax)."\n";
echo '- Final Price: '.Helpers::rupiah($tax->taxedPrice)."\n";
echo '- Amount in Words: '.Helpers::terbilang($tax->taxedPrice)."\n";

echo "\n=== Examples Complete ===\n";
