<?php

declare(strict_types=1);

namespace App\Services\PricingRules\Contracts;

use App\Models\Product;

interface PricingRuleInterface
{
    /**
     * Apply pricing rule to the given items.
     *
     * @param Product $product
     * @param int $quantity
     *
     * @return float
     *
     */
    public function apply(Product $product, int $quantity): float;
}
