<?php

declare(strict_types=1);


namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Checkout\Contracts\CheckoutInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class CheckoutController
 * @package App\Http\Controllers
 */
class CheckoutController extends Controller
{
    protected CheckoutInterface $checkoutService;

    public function __construct(CheckoutInterface $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    /**
     * Handle the checkout process.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkout(Request $request): JsonResponse
    {
        $productsData = $request->input('products');

        $co = $this->checkoutService;

        foreach ($productsData as $productData) {
            $product = Product::where('code', $productData['code'])->first();

            if (!$product || !$co->scan($product)) {
                return response()->json([
                    'error' => 'Failed to process product: ' . $productData['code']
                ], 400);
            }
        }

        return response()->json(['total' => $this->checkoutService->total()]);
    }
}
