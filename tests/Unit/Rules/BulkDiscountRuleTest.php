<?php

namespace Tests\Unit\Rules;

use App\Models\Product;
use App\Services\PricingRules\BulkDiscountRule;
use PHPUnit\Framework\TestCase;

class BulkDiscountRuleTest extends TestCase
{
    public function testApply()
    {
        $product = new Product([
            'code' => 'SR1',
            'name' => 'Strawberries',
            'price' => 5.00,
        ]);

        $rule = new BulkDiscountRule('SR1', 3, 4.50);
        $total = $rule->apply($product, 4); // Buy 4 strawberries

        $this->assertEquals(18.00, $total); // Expected total with discount
    }



}
