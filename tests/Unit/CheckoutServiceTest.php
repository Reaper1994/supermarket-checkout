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
        $this->seed(ProductsTableSeeder::class); // Seed the

        // Define pricing rules for the tests
        $pricingRules = config('pricing_rules.rules');

        // Initialize the CheckoutService with pricing rules
        $this->checkoutService = new CheckoutService($pricingRules);
    }

    public function testScanAndTotal()
    {

        $service =  $this->checkoutService;

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
        $service =  $this->checkoutService;

        $product1 = Product::where('code', 'FR1')->first();

        $service->scan($product1); // FR1
        $service->scan($product1); // FR1

        $total = $service->total();
        $this->assertEquals(3.11, $total); // Total price expected: £3.11
    }

    public function testScanAndTotalCase3()
    {
        $service =  $this->checkoutService;

        $product1 = Product::where('code', 'FR1')->first();
        $product2 = Product::where('code', 'SR1')->first();

        $service->scan($product2); // SR1
        $service->scan($product2); // SR1
        $service->scan($product1); // FR1
        $service->scan($product2); // SR1

        $total = $service->total();
        $this->assertEquals(16.61, $total); // Total price expected: £16.61
    }

    public function testScanReturnsFalseWhenInventoryStockIsZero(): void
    {
        // Create a product with inventory_stock set to 0
        $product1 = Product::where('code', 'FR1')->first();

        $product1->inventory_stock = 0;
        $product1->save();

        // Attempt to scan the product
        $result = $this->checkoutService->scan($product1);

        // Assert that the scan method returns false
        $this->assertFalse($result);
    }
}
