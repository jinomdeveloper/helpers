<?php

use Jinom\Helpers\Helpers;
use Jinom\Helpers\Services\TaxService;
use Jinom\Helpers\Facades\Helpers as HelpersFacade;

describe('Tax Calculations', function () {
    it('can calculate tax from inclusive price', function () {
        $taxService = Helpers::tax(111000, true);
        
        expect($taxService)->toBeInstanceOf(TaxService::class);
        expect($taxService->basePrice)->toBe(100000.0);
        expect($taxService->tax)->toBe(11000.0);
        expect($taxService->taxedPrice)->toBe(111000.0);
    });

    it('can calculate tax from exclusive price', function () {
        $taxService = Helpers::tax(100000, false);
        
        expect($taxService)->toBeInstanceOf(TaxService::class);
        expect($taxService->basePrice)->toBe(100000.0);
        expect($taxService->tax)->toBe(11000.0);
        expect($taxService->taxedPrice)->toBe(111000.0);
    });

    it('can handle decimal amounts correctly', function () {
        $taxService = Helpers::tax(100500.50, false);
        
        expect($taxService->basePrice)->toBe(100500.5);
        expect($taxService->tax)->toBe(11055.0); // rounded
        expect($taxService->taxedPrice)->toBe(111555.5);
    });

    it('can calculate tax using facade', function () {
        $taxService = HelpersFacade::tax(222000, true);
        expect($taxService->basePrice)->toBe(200000.0);
        expect($taxService->tax)->toBe(22000.0);
        expect($taxService->taxedPrice)->toBe(222000.0);
    });
});

describe('Currency Formatting', function () {
    it('can format rupiah correctly', function () {
        expect(Helpers::rupiah(1000000))->toBe('Rp 1.000.000');
        expect(Helpers::rupiah(150000))->toBe('Rp 150.000');
        expect(Helpers::rupiah(0))->toBe('Rp 0');
        expect(Helpers::rupiah(1500.75))->toBe('Rp 1.501'); // rounded
    });

    it('can handle negative amounts', function () {
        expect(Helpers::rupiah(-50000))->toBe('Rp -50.000');
    });

    it('can format rupiah using facade', function () {
        expect(HelpersFacade::rupiah(500000))->toBe('Rp 500.000');
    });
});

describe('Number to Words (Terbilang)', function () {
    it('can convert numbers to Indonesian words', function () {
        expect(Helpers::terbilang(0))->toBe('Rupiah');
        expect(Helpers::terbilang(1))->toBe('Satu Rupiah');
        expect(Helpers::terbilang(11))->toBe('Sebelas Rupiah');
        expect(Helpers::terbilang(25))->toBe('Dua Puluh Lima Rupiah');
        expect(Helpers::terbilang(100))->toBe('Seratus Rupiah');
        expect(Helpers::terbilang(1000))->toBe('Seribu Rupiah');
        expect(Helpers::terbilang(1500))->toBe('Seribu Lima Ratus Rupiah');
        expect(Helpers::terbilang(150000))->toBe('Seratus Lima Puluh Ribu Rupiah');
        expect(Helpers::terbilang(1000000))->toBe('Satu Juta Rupiah');
    });

    it('can convert using facade', function () {
        expect(HelpersFacade::terbilang(25000))->toBe('Dua Puluh Lima Ribu Rupiah');
    });
});

describe('Currency Parsing', function () {
    it('can parse currency strings to numbers', function () {
        expect(Helpers::rupiah_to_number('Rp 1.000.000'))->toBe(1000000);
        expect(Helpers::rupiah_to_number('Rp 150.000'))->toBe(150000);
        expect(Helpers::rupiah_to_number('1.500,75'))->toBe(1500.75);
        expect(Helpers::rupiah_to_number('(Rp 50.000)'))->toBe(-50000);
        expect(Helpers::rupiah_to_number('-Rp 25.000'))->toBe(-25000);
        expect(Helpers::rupiah_to_number(150000))->toBe(150000);
        expect(Helpers::rupiah_to_number(''))->toBe(0);
        expect(Helpers::rupiah_to_number('0'))->toBe(0);
    });

    it('can parse using facade', function () {
        expect(HelpersFacade::rupiah_to_number('Rp 500.000'))->toBe(500000);
    });
});

describe('Phone Number Formatting', function () {
    it('can format Indonesian phone numbers to E.164', function () {
        expect(Helpers::to_e164('081234567890'))->toBe('+6281234567890');
        expect(Helpers::to_e164('0812-3456-7890'))->toBe('+6281234567890');
        expect(Helpers::to_e164('62812345678'))->toBe('+62812345678');
        expect(Helpers::to_e164('+6281234567890'))->toBe('+6281234567890');
        expect(Helpers::to_e164('81234567890'))->toBe('+6281234567890');
        expect(Helpers::to_e164(''))->toBe('');
    });

    it('can handle custom country codes', function () {
        expect(Helpers::to_e164('081234567890', '1'))->toBe('+181234567890');
        expect(Helpers::to_e164('0812345678', '44'))->toBe('+44812345678');
    });

    it('can format using facade', function () {
        expect(HelpersFacade::to_e164('081234567890'))->toBe('+6281234567890');
    });
});

describe('Integration Tests', function () {
    it('can process e-commerce scenario', function () {
        // Product price displayed to customer (includes tax)
        $displayPrice = 555000;
        $tax = Helpers::tax($displayPrice, true);
        
        expect($tax->basePrice)->toBe(500000.0);
        expect($tax->tax)->toBe(55000.0);
        expect($tax->taxedPrice)->toBe(555000.0);
        
        // Format for display
        $formattedBasePrice = Helpers::rupiah($tax->basePrice);
        $formattedTax = Helpers::rupiah($tax->tax);
        $formattedTotal = Helpers::rupiah($tax->taxedPrice);
        $amountInWords = Helpers::terbilang($tax->taxedPrice);
        
        expect($formattedBasePrice)->toBe('Rp 500.000');
        expect($formattedTax)->toBe('Rp 55.000');
        expect($formattedTotal)->toBe('Rp 555.000');
        expect($amountInWords)->toBe('Lima Ratus Lima Puluh Lima Ribu Rupiah');
    });
    
    it('can process invoice scenario', function () {
        $items = [
            ['name' => 'Product A', 'price' => 100000],
            ['name' => 'Product B', 'price' => 250000],
        ];
        
        $subtotal = 0;
        $totalTax = 0;
        
        foreach ($items as $item) {
            $tax = Helpers::tax($item['price'], false);
            $subtotal += $tax->basePrice;
            $totalTax += $tax->tax;
        }
        
        expect($subtotal)->toBe(350000.0);
        expect($totalTax)->toBe(38500.0);
        expect($subtotal + $totalTax)->toBe(388500.0);
        
        // Format amounts
        expect(Helpers::rupiah($subtotal))->toBe('Rp 350.000');
        expect(Helpers::rupiah($totalTax))->toBe('Rp 38.500');
        expect(Helpers::rupiah($subtotal + $totalTax))->toBe('Rp 388.500');
    });

    it('can process customer form data', function () {
        // Simulate customer form input
        $customerInput = [
            'amount' => 'Rp 1.500.000',
            'phone' => '0812-3456-7890'
        ];
        
        // Parse and process
        $amount = Helpers::rupiah_to_number($customerInput['amount']);
        $phone = Helpers::to_e164($customerInput['phone']);
        
        expect($amount)->toBe(1500000);
        expect($phone)->toBe('+6281234567890');
        
        // Calculate with tax
        $tax = Helpers::tax($amount, false);
        
        expect($tax->taxedPrice)->toBe(1665000.0);
        expect(Helpers::rupiah($tax->taxedPrice))->toBe('Rp 1.665.000');
        expect(Helpers::terbilang($tax->taxedPrice))->toBe('Satu Juta Enam Ratus Enam Puluh Lima Ribu Rupiah');
    });
});
