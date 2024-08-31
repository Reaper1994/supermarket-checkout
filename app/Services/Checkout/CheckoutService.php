<?php
declare(strict_types=1);

namespace App\Services\Checkout;

use App\Models\Product;
use App\Services\Checkout\Contracts\CheckoutInterface;

/**
 * Class CheckoutService
 * @package App\Services
 */
class CheckoutService implements CheckoutInterface
{
    protected array $pricingRules;
    protected array $items = [];
    protected array $quantities = [];

    public function __construct(array $pricingRules)
    {
        $this->pricingRules = $pricingRules;
    }

    /**
     * Scans a product to update against inventory stock.
     *
     * @param Product $product
     * @return bool
     */
    public function scan(Product $product): bool
    {
        $code = $product->code;

        if ($product->inventory_stock > 0) {
            if (!isset($this->items[$code])) {
                $this->items[$code] = $product;
                $this->quantities[$code] = 0;
            }

            $this->quantities[$code]++;
            $product->inventory_stock--;
            $product->save();

            return true;
        }

        return false;
    }

    /**
     * Calculate total price.
     *
     * @return float
     */
    public function total(): float
    {
        $total = 0.0;
        $pricingRuleInstances = [];

        // Instantiate pricing rules
        foreach ($this->pricingRules as $ruleConfig) {
            $class = $ruleConfig['class'];
            $params = $ruleConfig['params'];

            // Instantiate the rule
            $rule = new $class(...$params);

            // Register the rule instance for applicable product codes
            foreach ($params[0] as $code) {
                $pricingRuleInstances[$code] = $rule;
            }
        }

        // Calculate total
        foreach ($this->items as $code => $product) {
            $pricingRule = $pricingRuleInstances[$code] ?? null;
            $quantity = $this->quantities[$code] ?? 0;

            if ($pricingRule) {
                $total += $pricingRule->apply($product, $quantity);
            } else {
                $total += $product->price * $quantity;
            }
        }

        return round($total, 2);
    }
}
