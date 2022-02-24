<?php

require_once '../vendor/autoload.php';

use Faslet\OrderTracking;

$orderTracking = new OrderTracking("Faslet Demo");

$orderTracking
    ->withOrderNumber("order-123")
    ->withPaymentStatus("paid");

$orderTracking
    ->addProduct("product-1", "variant-1-1", "Jacket", "Medium/Blue", 400, 2, "sku1")
    ->addProduct("product-2", "variant-2-1", "T-Shirt", "Medium/Blue", 100, 1, "sku2");

?>
<!DOCTYPE html>
<html>

<body>
    <div id="post-checkout-page">
        <?php
        echo $orderTracking->buildOrderTracking()
        ?>
    </div>
</body>

</html>