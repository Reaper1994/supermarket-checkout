<?php

namespace Tests\Feature;

use App\Services\Checkout\CheckoutService;
use Database\Seeders\ProductsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProductsTableSeeder::class); // Seed the database
    }

    protected function tearDown(): void
    {
        // Any necessary cleanup after each test
        parent::tearDown();
    }

    public function testCheckoutCase1()
    {
        $pricingRules = config('pricing_rules.rules');
        $service = new CheckoutService($pricingRules);

        $response = $this->postJson('/api/checkout', [
            'products' => [
                ['code' => 'FR1'],
                ['code' => 'SR1'],
                ['code' => 'FR1'],
                ['code' => 'FR1'],
                ['code' => 'CF1'],
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson(['total' => 22.45]);
    }

    public function testCheckoutCase2()
    {
        $pricingRules = config('pricing_rules.rules');
        $service = new CheckoutService($pricingRules);

        $response = $this->postJson('/api/checkout', [
            'products' => [
                ['code' => 'FR1'],
                ['code' => 'FR1'],
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson(['total' => 3.11]);
    }

    public function testCheckoutCase3()
    {
        $pricingRules = config('pricing_rules.rules');
        $service = new CheckoutService($pricingRules);

        $response = $this->postJson('/api/checkout', [
            'products' => [
                ['code' => 'SR1'],
                ['code' => 'SR1'],
                ['code' => 'FR1'],
                ['code' => 'SR1'],
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson(['total' => 16.61]);
    }
}
