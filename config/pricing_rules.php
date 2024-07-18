<?php

return [
    'rules' => [
        [
            'class' => \App\Services\PricingRules\BuyOneGetOneFreeRule::class,
            'params' => ['FR1'], // product_code
            'product_code' => 'FR1'
        ],
        [
            'class' => \App\Services\PricingRules\BulkDiscountRule::class,
            'params' => ['SR1', 3, 4.50], // product_code , units , discount
            'product_code' => 'SR1'
        ],
    ],
];
