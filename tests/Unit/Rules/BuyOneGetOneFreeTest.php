<?php

namespace Tests\Unit\Rules;

use App\Exceptions\InvalidQuantityException;
use App\Models\Product;
use App\Services\PricingRules\BuyOneGetOneFreeRule;
use PHPUnit\Framework\TestCase;

class BuyOneGetOneFreeTest extends TestCase
{
    private function createProduct(string $code, string $name, float $price): Product
    {
        return new Product([
            'code' => $code,
            'name' => $name,
            'price' => $price,
        ]);
    }

    private function createRule(string $productCode): BuyOneGetOneFreeRule
    {
        return new BuyOneGetOneFreeRule($productCode);
    }

    public function testApplyWithOneItem()
    {
        $product = $this->createProduct('FR1', 'Fruit Tea', 3.11);
        $rule = $this->createRule('FR1');

        $total = $rule->apply($product, 1); // Buy 1, get 1 free

        $this->assertEquals(3.11, $total); // Expected total: Pay for 1 item (3.11 * 1)
    }

    public function testApplyWithTwoItems()
    {
        $product = $this->createProduct('FR1', 'Fruit Tea', 3.11);
        $rule = $this->createRule('FR1');

        $total = $rule->apply($product, 2); // Buy 2, get 1 free

        $this->assertEquals(3.11, $total); // Expected total: Pay for 1 item (3.11 * 1)
    }

    public function testApplyWithThreeItems()
    {
        $product = $this->createProduct('FR1', 'Fruit Tea', 3.11);
        $rule = $this->createRule('FR1');

        $total = $rule->apply($product, 3); // Buy 3, get 1 free

        $this->assertEquals(6.22, $total); // Expected total: Pay for 2 items (3.11 * 2)
    }

    public function testApplyWithFourItems()
    {
        $product = $this->createProduct('FR1', 'Fruit Tea', 3.11);
        $rule = $this->createRule('FR1');

        $total = $rule->apply($product, 4); // Buy 4, get 2 free

        $this->assertEquals(6.22, $total); // Expected total: Pay for 2 items (3.11 * 2)
    }

    public function testApplyNegativeQuantity()
    {
        $this->expectException(InvalidQuantityException::class);
        $this->expectExceptionMessage('Quantity cannot be negative.');

        $product = $this->createProduct('FR1', 'Fruit Tea', 3.11);
        $rule = $this->createRule('FR1');

        $rule->apply($product, -1); // Apply rule with negative quantity
    }

    public function testApplyWithDifferentProductCode()
    {
        $product = $this->createProduct('CF1', 'Coffee', 11.23);
        $rule = $this->createRule('FR1');

        $total = $rule->apply($product, 3); // Buy 3 of a different product

        $this->assertEquals(33.69, $total); // Expected total without discount (11.23 * 3)
    }
}
