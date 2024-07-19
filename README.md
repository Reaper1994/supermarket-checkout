# Supermarket Checkout System

This project implements a supermarket checkout system that applies various pricing rules. It includes both a service layer for processing checkout operations and unit tests to ensure correctness.

## Features

- **Buy One Get One Free**: Applies to specific products.
- **Bulk Discount**: Applies discounts when a minimum quantity of a product is purchased.
- **Flexible Pricing Rules**: Configurable via a PHP configuration file.

## Installation

1. **Clone the repository:**

    ```bash
    cd supermarket-checkout
    ```

2. **Install dependencies:**

    ```bash
    composer install
    ```

3. **Setup environment:**

    Copy the example environment file and adjust the configuration as needed ie setup the db creds:

    ```bash
    cp .env.example .env
    ```

4. **Run database migrations and seeders:**

    ```bash
    php artisan migrate --seed
    ```

## Configuration

The pricing rules are configured in `config/pricing_rules.php`:

```bash
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
```

## Usage

To perform a checkout, use the CheckoutService:

```bash
use App\Models\Product;
use App\Services\Checkout\CheckoutService;

$pricingRules = config('pricing_rules.rules');
$checkout = new CheckoutService($pricingRules);

$product1 = Product::where('code', 'FR1')->first();
$product2 = Product::where('code', 'SR1')->first();

$checkout->scan($product1);
$checkout->scan($product2);

$total = $checkout->total();
echo "Total: $total";
```

## Testing
After setting up the PHPUnit.xml file  run the following command 


```bash
php artisan test --filter Feature or --filter Unit
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Author
 Osmar Rodrigues (https://github.com/Reaper1994)

