<?php

require_once '../vendor/autoload.php';

use Faslet\Widget;

$widget = new Widget("Faslet Demo");

$widget
    ->withBrand("Faslet Demo Brand")
    ->withProductId("id123")
    ->withProductImage("https://placekitten.com/100")
    ->withProductName("Jacket")
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

$widget->withAddToCartRedirect("https://example.com/add-to-cart?variantId=%id%", "%id%")

?>
<!DOCTYPE html>
<html>

<head>
    <?php
    echo Widget::buildScriptTag()
    ?>
</head>

<body>
    <div id="product-page">
        <?php
        echo $widget->buildWidget()
        ?>
    </div>
</body>

</html>