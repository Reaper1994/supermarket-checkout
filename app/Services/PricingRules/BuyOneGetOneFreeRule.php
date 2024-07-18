<?php

declare(strict_types=1);

namespace App\Services\PricingRules;

use App\Models\Product;
use App\Services\PricingRules\Contracts\PricingRuleInterface;
use function Symfony\Component\String\s;

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
     *@param int $quantity
     *
    * @return float
    */
    public function apply(Product $product, int $quantity): float
    {

        if ($product->code === $this->productCode) {
            // Calculate price considering buy one, get one free offer

            $quantity = match (true) {
                $quantity === 1 => $quantity,
                $quantity > 1 => $quantity - 1,
                $quantity < 1 => 0,
            };

            return $quantity * $product->price;
        }

        return $quantity * $product->price;
    }
}
