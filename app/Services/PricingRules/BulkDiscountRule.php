<?php

namespace App\Services\PricingRules;

use App\Services\PricingRules\Contracts\PricingRuleInterface;

/**
 * Class BulkDiscountRule
 * @package App\Services\PricingRules
 */
class BulkDiscountRuleService implements PricingRuleInterface {
    private $productCode;
    private $threshold;
    private $discountedPrice;

    /**
     * BulkDiscountRule constructor.
     *
     * @param string $productCode
     * @param int $threshold
     * @param float $discountedPrice
     */
    public function __construct(string $productCode, int $threshold, float $discountedPrice)
    {
        $this->productCode = $productCode;
        $this->threshold = $threshold;
        $this->discountedPrice = $discountedPrice;
    }

    /**
     * Apply bulk discount pricing rule.
     *
     * @param array $items
     * @return float
     */
    public function apply(array $items): float
    {
        $count = 0;
        $total = 0.0;

        foreach ($items as $item) {
            if ($item['code'] === $this->productCode) {
                $count++;
            }
        }

        foreach ($items as $item) {
            if ($item['code'] === $this->productCode && $count >= $this->threshold) {
                $total += $this->discountedPrice;
            } else {
                $total += $item['price'];
            }
        }

        return $total;
    }
}
