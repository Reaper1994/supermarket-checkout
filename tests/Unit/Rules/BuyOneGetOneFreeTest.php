<?php

namespace Tests\Unit\Rules;

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
}
