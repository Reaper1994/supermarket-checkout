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

        foreach ($this->items as $code => $product) {
            $pricingRule = null;

            foreach ($this->pricingRules as $rule) {
                if ($code === $rule['product_code']) {
                    $pricingRule = new $rule['class'](...$rule['params']);
                    break; // Exit the loop once the matching rule is found
                }
            }

            if ($pricingRule) {
                $total += $pricingRule->apply($product, $this->quantities[$code]);
            } else {
                $total += $product->price * $this->quantities[$code];
            }
        }

        return round($total, 2);
    }
}
