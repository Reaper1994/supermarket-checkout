<?php

namespace App\Services\Checkout;

use App\Models\Product;


/**
 * Class Checkout
 * @package App\Services
 */
class CheckoutService implements CheckoutInterface
{
    private $pricingRules;
    private $items = [];

    /**
     * Checkout constructor.
     *
     * @param PricingRuleInterface[] $pricingRules
     */
    public function __construct(array $pricingRules)
    {
        $this->pricingRules = $pricingRules;
    }

    /**
     * Scan an item.
     *
     * @param Product $item
     */
    public function scan(Product $item)
    {
        $this->items[] = $item;
    }

    /**
     * Calculate total price.
     *
     * @return float
     */
    public function total(): float
    {
        $total = 0.0;

        foreach ($this->pricingRules as $rule) {
            $total = $rule->apply($this->items);
        }

        return $total;
    }
}
