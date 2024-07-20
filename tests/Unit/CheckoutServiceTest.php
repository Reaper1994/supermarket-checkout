<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\Checkout\CheckoutService;
use Database\Seeders\ProductsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutServiceTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProductsTableSeeder::class); // Seed the database
    }

    public function testScanAndTotal()
    {
        $pricingRules = config('pricing_rules.rules');

        $service = new CheckoutService($pricingRules);

        $product1 = Product::where('code', 'FR1')->first();
        $product2 = Product::where('code', 'SR1')->first();
        $product3 = Product::where('code', 'CF1')->first();

        $service->scan($product1); // FR1

        $service->scan($product2); // SR1

        $service->scan($product1); // FR1

        $service->scan($product1); // FR1

        $service->scan($product3); // CF1


        $total = $service->total();
        $this->assertEquals(22.45, $total); // Total price expected: £22.45
    }


    public function testScanAndTotalCase2()
    {
        $pricingRules = config('pricing_rules.rules');
        $service = new CheckoutService($pricingRules);

        $product1 = Product::where('code', 'FR1')->first();

        $service->scan($product1); // FR1
        $service->scan($product1); // FR1

        $total = $service->total();
        $this->assertEquals(3.11, $total); // Total price expected: £3.11
    }

    public function testScanAndTotalCase3()
    {
        $pricingRules = config('pricing_rules.rules');
        $service = new CheckoutService($pricingRules);

        $product1 = Product::where('code', 'FR1')->first();
        $product2 = Product::where('code', 'SR1')->first();

        $service->scan($product2); // SR1
        $service->scan($product2); // SR1
        $service->scan($product1); // FR1
        $service->scan($product2); // SR1

        $total = $service->total();
        $this->assertEquals(16.61, $total); // Total price expected: £16.61
    }

    public function testScanFails()
    {
        $pricingRules = config('pricing_rules.rules');
        $service = new CheckoutService($pricingRules);

        // Set the product's inventory stock to 0
        $product = Product::where('code', 'FR1')->first();
        $product->inventory_stock = 0;
        $product->save();

        // Try to scan the product with 0 inventory
        $result = $service->scan($product);

        // Assert that the scan method returns false
        $this->assertFalse($result);

        // Assert that the total is still 0.00
        $total = $service->total();
        $this->assertEquals(0.00, $total); // Total price expected: £0.00
    }
}
