<?php

namespace App\Services\PricingRules;

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
     * Factory method to create an instance based on configuration.
     *
     * @return BulkDiscountRule
     */
    public static function createFromConfig(): self
    {
        $config = config('pricing_rules.rules');

        foreach ($config as $rule) {
            if ($rule['class'] === self::class) {
                return new self(
                    $rule['params']['product_code'],
                    $rule['params']['threshold'],
                    $rule['params']['discount_price']
                );
            }
        }

        throw new \RuntimeException('BulkDiscountRule configuration not found.');
    }

    /**
     * Apply bulk discount pricing rule.
     *
     * @param Product $product
     * @param int $quantity
     *
     * @return float
     */
    public function apply(Product $product, int $quantity): float
    {
        if ($product->code === $this->productCode) {
            if ($quantity >= $this->threshold) {
                return $quantity * $this->discountPrice;
            } else {
                return $quantity * $product->price;
            }
        }

        return $quantity * $product->price;
    }
}
