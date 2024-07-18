<?php

namespace App\Services\PricingRule;

use App\Services\PricingRule\Contracts\PricingRuleInterface;

/**
* Class BuyOneGetOneFreeRule
* @package App\Services\Contracts\PricingRules
*/
class BuyOneGetOneFreeRule implements PricingRuleInterface
{
private $productCode;

/**
* BuyOneGetOneFreeRule constructor.
*
* @param string $productCode
*/
public function __construct(string $productCode)
{
    $this->productCode = $productCode;
}

/**
* Apply buy-one-get-one-free pricing rule.
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
            if ($count % 2 != 0) {
                $total += $item['price'];
            }
        } else {
                $total += $item['price'];
        }
    }

        return $total;
    }
}
