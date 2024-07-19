<?php

declare(strict_types=1);

namespace App\Services\PricingRules;

use App\Exceptions\InvalidQuantityException;
use App\Models\Product;
use App\Services\PricingRules\Contracts\PricingRuleInterface;

/**
 * Class BulkDiscountRule
 * @package App\Services\PricingRules
 */
class BulkDiscountRule implements PricingRuleInterface {
    protected string $productCode;
    protected int $threshold;
    protected float $discountPrice;

    /**
     * Private constructor to enforce usage of factory method.
     */
    public function __construct(string $productCode, int $threshold, float $discountPrice)
    {
        $this->productCode = $productCode;
        $this->threshold = $threshold;
        $this->discountPrice = $discountPrice;
    }

    /**
     * Apply bulk discount pricing rule.
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

        if ($product->code === $this->productCode) {
            $price = $quantity >= $this->threshold ? $this->discountPrice : $product->price;
            return $quantity * $price;
        }

        return $quantity * $product->price;
    }
}
