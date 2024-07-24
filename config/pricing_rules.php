<?php

return [
    'rules' => [
        [
            'class' => \App\Services\PricingRules\BuyOneGetOneFreeRule::class,
            'params' => [
                ['FR1'] // array of product_codes on which discount is to be applied on
            ],
        ],
        [
            'class' => \App\Services\PricingRules\BulkDiscountRule::class,
            'params' => [
                ['SR1'], // array of product_codes on which discount is to be applied on
                3, // units
                4.50], //discount
        ],
    ],
];
