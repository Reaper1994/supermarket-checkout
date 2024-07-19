<?php

declare(strict_types=1);

namespace App\Services\PricingRules;

use App\Models\Product;
use App\Services\PricingRules\Contracts\PricingRuleInterface;
use App\Exceptions\InvalidQuantityException;

/**
* Class BuyOneGetOneFreeRule
* @package App\Services\Contracts\PricingRules
*/
class BuyOneGetOneFreeRule implements PricingRuleInterface
{
    protected string $productCode;

    /**
     * @param string $productCode
     */
    public function __construct(string $productCode)
    {
        $this->productCode = $productCode;

    }

    /**
     * Apply buy-one-get-one-free pricing rule.
     *
     * @param Product $product
     * @param int $quantity
     *
     * @return float
     * @throws InvalidQuantityException
     */
    public function apply(Product $product, int $quantity): float
    {
        if ($quantity < 0) {
            throw new InvalidQuantityException('Quantity cannot be negative.');
        }

        if ($product->code !== $this->productCode) {
            // No rule applies if the product code doesn't match
            return $quantity * $product->price;
        }

        // Calculate the effective quantity to pay for
        $payableQuantity = intdiv($quantity + 1, 2); // For every 2 items, pay for 1

        return $payableQuantity * $product->price;
    }
}
