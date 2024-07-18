<?php

namespace Tests\Unit\Rules;

use App\Models\Product;
use App\Services\PricingRules\BuyOneGetOneFreeRule;
use PHPUnit\Framework\TestCase;

class BuyOneGetOneFreeTest extends TestCase
{
    public function testApply()
    {
        $product = new Product([
            'code' => 'FR1',
            'name' => 'Fruit Tea',
            'price' => 3.11,
        ]);

        $rule = new BuyOneGetOneFreeRule('FR1');
        $total = $rule->apply($product, 3); // Buy 3, get 1 free

        $this->assertEquals(6.22, $total); // Expected total
    }
}
