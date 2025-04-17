<?php

declare(strict_types=1);

namespace App\Services\Checkout\Contracts;

use App\Models\Product;

interface CheckoutInterface
{
    /**
     * Scans an Item updates inventory.
     *
     * @param Product $product
     * @return bool
     */
    public function scan(Product $product): bool;

    /**
     * Calculates the total
     * @return float
     */
    public function total(): float;
}
