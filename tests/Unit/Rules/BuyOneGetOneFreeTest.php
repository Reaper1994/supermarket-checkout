<?php

namespace Tests\Unit\Rules;

use App\Exceptions\InvalidQuantityException;
use App\Models\Product;
use App\Services\PricingRules\BuyOneGetOneFreeRule;
use PHPUnit\Framework\TestCase;

class BuyOneGetOneFreeTest extends TestCase
{
    public function testApplyWithOneItem()
    {
        $product = new Product([
            'code' => 'FR1',
            'name' => 'Fruit Tea',
            'price' => 3.11,
        ]);

        $rule = new BuyOneGetOneFreeRule('FR1');
        $total = $rule->apply($product, 1); // Buy 1, get 1 free

        $this->assertEquals(3.11, $total); // Expected total: Pay for 1 item (3.11 * 1)
    }

    public function testApplyWithTwoItems()
    {
        $product = new Product([
            'code' => 'FR1',
            'name' => 'Fruit Tea',
            'price' => 3.11,
        ]);

        $rule = new BuyOneGetOneFreeRule('FR1');
        $total = $rule->apply($product, 2); // Buy 2, get 1 free

        $this->assertEquals(3.11, $total); // Expected total: Pay for 1 item (3.11 * 1)
    }

    public function testApplyWithThreeItems()
    {
        $product = new Product([
            'code' => 'FR1',
            'name' => 'Fruit Tea',
            'price' => 3.11,
        ]);

        $rule = new BuyOneGetOneFreeRule('FR1');
        $total = $rule->apply($product, 3); // Buy 3, get 1 free

        $this->assertEquals(6.22, $total); // Expected total: Pay for 2 items (3.11 * 2)
    }

    public function testApplyWithFourItems()
    {
        $product = new Product([
            'code' => 'FR1',
            'name' => 'Fruit Tea',
            'price' => 3.11,
        ]);

        $rule = new BuyOneGetOneFreeRule('FR1');
        $total = $rule->apply($product, 4); // Buy 4, get 2 free

        $this->assertEquals(6.22, $total); // Expected total: Pay for 2 items (3.11 * 2)
    }

    public function testApplyNegativeQuantity()
    {
        $this->expectException(InvalidQuantityException::class);
        $this->expectExceptionMessage('Quantity cannot be negative.');

        $product = new Product([
            'code' => 'FR1',
            'name' => 'Fruit Tea',
            'price' => 3.11,
        ]);

        $rule = new BuyOneGetOneFreeRule('FR1');
        $rule->apply($product, -1); // Apply rule with negative quantity
    }

    public function testApplyWithDifferentProductCode()
    {
        $product = new Product([
            'code' => 'CF1',
            'name' => 'Coffee',
            'price' => 11.23,
        ]);

        $rule = new BuyOneGetOneFreeRule('FR1');
        $total = $rule->apply($product, 3); // Buy 3 of a different product

        $this->assertEquals(33.69, $total); // Expected total without discount (3.11 * 3)
    }
}
