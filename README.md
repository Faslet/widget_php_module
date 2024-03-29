# Faslet Widget Composer Plugin

### Usage
To use this project in your own, pull down this plugin with 
```bash
composer require faslet/faslet
```

#### Widget (Product page)

Example usage with shop id `Faslet Demo`
```php
use Faslet\Widget;

// GET THIS FROM YOUR FASLET REPRESENTITIVE
$widget = new Widget("Faslet Demo");

$widget
    ->withBrand("Faslet Demo Brand")
    ->withProductId("id123")
    ->withProductImage("https://placekitten.com/100")
    ->withProductName("Jacket")
    // GET THIS FROM YOUR FASLET REPRESENTITIVE
    ->withFasletProductTag("Faslet_Jacket_Male");

$widget
    ->addColor("red", "Magnificent Red")
    ->addColor("blue", "Dashing Blue");


$widget
    ->addVariant("var_1", "S", true, "sku_1", "red", 59.99, 'https://placekitten.com')
    ->addVariant("var_2", "S", true, "sku_2", "blue", 59.99, 'https://placekitten.com')
    ->addVariant("var_3", "M", true, "sku_3", "red", 49.99, 'https://placekitten.com')
    ->addVariant("var_4", "M", false, "sku_4", "blue", 49.99, 'https://placekitten.com')
    ->addVariant("var_5", "L", false, "sku_5", "red", 59.99, 'https://placekitten.com')
    ->addVariant("var_6", "L", false, "sku_6", "blue", 59.99, 'https://placekitten.com');

$widget->withAddToCartRedirect("https://example.com/add-to-cart?variantId=%id%", "%id%");
```
and then to render the HTML
```php
echo $widget->buildWidget();
```

#### Order Tracking (After checkout/Thank You page)
Example usage with shop id `Faslet Demo`
```php
use Faslet\OrderTracking;

// GET THIS FROM YOUR FASLET REPRESENTITIVE
$orderTracking = new OrderTracking("Faslet Demo");

$orderTracking
    ->withOrderNumber("order-123")
    ->withPaymentStatus("paid");

$orderTracking
    ->addProduct("product-1", "variant-1-1", "Jacket", "Medium/Blue", 400, 2, "sku1")
    ->addProduct("product-2", "variant-2-1", "T-Shirt", "Medium/Blue", 100, 1, "sku2");
```

and then to render the HTML
```php
echo $orderTracking->buildOrderTracking();
```

### Examples

To run the built in examples, the easiest way is to run the following command in the root project folder:

```bash
php -S localhost:5002
```

And then navigate to:

http://localhost:5002/examples/faslet-widget.php

for the Widget example and

http://localhost:5002/examples/faslet-order-tracking.php

for the Order Tracking example. Note that Order tracking only sends events, which you would see in the network tab of your browser's dev-tools.


### Development

This project uses composer. First run install before starting development:

```bash
composer install
```

While developing, you may need to regenerate the autoload:
```bash
composer dump-autoload
```

### Testing

This project uses PHP  Unit for testing. To run, simply run the following command:
```bash
./vendor/bin/phpunit --testdox tests
```
