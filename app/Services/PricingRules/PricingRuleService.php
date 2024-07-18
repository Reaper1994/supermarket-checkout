<?php

namespace App\Services\PricingRules;



use App\Services\PricingRules\Contracts\PricingRuleInterface;

class PricingRuleService
{
    protected array $rules = [];

    /**
     * @param PricingRuleInterface $rule
     * @return void
     */
    public function addRule(PricingRuleInterface $rule): void
    {
        $this->rules[] = $rule;
    }

    /**
     * @param array $items
     * @param array $quantities
     * @return float
     */
    public function calculateTotal(array $items, array $quantities): float
    {
        $total = 0;

        foreach ($this->rules as $rule) {
            $total += $rule->apply($items, $quantities);
        }

        return $total;
    }
}
