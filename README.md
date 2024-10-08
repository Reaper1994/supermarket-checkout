# Supermarket Checkout System

This project implements a flexible supermarket checkout system that applies various pricing rules. It includes a service layer for processing checkout operations and unit tests to ensure correctness.

## Products in the Database
Currently, we have only three products in the database:

| Product Code | Name         | Price  |
|--------------|--------------|--------|
| FR1          | Fruit Tea    | £3.11  |
| SR1          | Strawberries | £5.00  |
| CF1          | Coffee       | £11.23 |


## Features

- **Buy One Get One Free**: Applies to specific products. (Currently configured for product code 'FR1' as you will see in the config below)
- **Bulk Discount**: Applies discounts when a minimum quantity of a product is purchased. (Currently configured for product code 'SR1'- when you buy 3 you get one free-  as you will see in the config below)
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

    Copy the example environment file adjust the configuration as needed ie setup the db creds:

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
```
## Usage using cURL

```bash
curl -X POST http://yourdomain.com/api/checkout \
     -H "Content-Type: application/json" \
     -d '{"products":[{"code":"FR1"},{"code":"SR1"}]}'
```
### Success Response
```bash
{
    "total": 10.50
}
````
### Error Response:
```json
{
    "error": "Failed to process product: FR1"
}
```

## Testing
After setting up the PHPUnit.xml file  run the following command 


```bash
php artisan test --filter Feature or --filter Unit
```
Currently, the coverage is 100%!
## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Author
 Osmar Rodrigues (https://github.com/Reaper1994)

