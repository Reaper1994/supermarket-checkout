<?php

namespace Tests\Unit\Rules;

use App\Models\Product;
use App\Services\PricingRules\BulkDiscountRule;
use App\Exceptions\InvalidQuantityException;
use PHPUnit\Framework\TestCase;

class BulkDiscountRuleTest extends TestCase
{
    private function createProduct(string $code, string $name, float $price): Product
    {
        return new Product([
            'code' => $code,
            'name' => $name,
            'price' => $price,
        ]);
    }

    private function createRule(string $productCode, int $threshold, float $discountPrice): BulkDiscountRule
    {
        return new BulkDiscountRule($productCode, $threshold, $discountPrice);
    }

    public function testApplyBelowThreshold()
    {
        $product = $this->createProduct('SR1', 'Strawberries', 5.00);
        $rule = $this->createRule('SR1', 3, 4.50);

        $total = $rule->apply($product, 2); // Buy 2 strawberries

        $this->assertEquals(10.00, $total); // Expected total without discount
    }

    public function testApplyAtThreshold()
    {
        $product = $this->createProduct('SR1', 'Strawberries', 5.00);
        $rule = $this->createRule('SR1', 3, 4.50);

        $total = $rule->apply($product, 3); // Buy 3 strawberries

        $this->assertEquals(13.50, $total); // Expected total with discount
    }

    public function testApplyAboveThreshold()
    {
        $product = $this->createProduct('SR1', 'Strawberries', 5.00);
        $rule = $this->createRule('SR1', 3, 4.50);

        $total = $rule->apply($product, 4); // Buy 4 strawberries

        $this->assertEquals(18.00, $total); // Expected total with discount
    }

    public function testApplyWithDifferentDiscountPrice()
    {
        $product = $this->createProduct('SR1', 'Strawberries', 5.00);
        $rule = $this->createRule('SR1', 3, 3.50);

        $total = $rule->apply($product, 4); // Buy 4 strawberries

        $this->assertEquals(14.00, $total); // Expected total with discount
    }

    public function testApplyWithDifferentThreshold()
    {
        $product = $this->createProduct('SR1', 'Strawberries', 5.00);
        $rule = $this->createRule('SR1', 5, 4.00);

        $total = $rule->apply($product, 5); // Buy 5 strawberries

        $this->assertEquals(20.00, $total); // Expected total with discount
    }

    public function testApplyDifferentProduct()
    {
        $product = $this->createProduct('SR2', 'Blueberries', 5.00);
        $rule = $this->createRule('SR1', 3, 4.50);

        $total = $rule->apply($product, 4); // Buy 4 blueberries

        $this->assertEquals(20.00, $total); // Expected total without discount
    }

    public function testApplyZeroQuantity()
    {
        $product = $this->createProduct('SR1', 'Strawberries', 5.00);
        $rule = $this->createRule('SR1', 3, 4.50);

        $total = $rule->apply($product, 0); // Buy 0 strawberries

        $this->assertEquals(0.00, $total); // Expected total
    }

    public function testApplyNegativeQuantity()
    {
        $this->expectException(InvalidQuantityException::class);
        $this->expectExceptionMessage('Quantity cannot be negative.');

        $product = $this->createProduct('SR1', 'Strawberries', 5.00);
        $rule = $this->createRule('SR1', 3, 4.50);

        $rule->apply($product, -1); // Apply rule with negative quantity
    }
}
