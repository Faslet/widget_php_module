<?php

declare(strict_types=1);

namespace Faslet\Tests;

use Faslet\MissingParameterException;
use PHPUnit\Framework\TestCase;

final class OrderTrackingTest extends TestCase
{
  public function testSetsShopIdOnConstruction(): void
  {
    $testShopId = 'test shop id';
    $orderTracking = new \Faslet\OrderTracking($testShopId);
    $this->assertEquals($orderTracking->getShopId(), $testShopId);
  }

  public function testReturnsAValidOrderTrackingTag(): void
  {
    $testShopId = 'test shop id';
    $orderTracking = new \Faslet\OrderTracking($testShopId);
    $orderTracking
      ->withOrderNumber("order-123")
      ->withPaymentStatus("paid");

    $orderTracking
      ->addProduct("product-1", "variant-1-1", "Jacket", "Medium/Blue", 400, 2, "sku1")
      ->addProduct("product-2", "variant-2-1", "T-Shirt", "Medium/Blue", 100, 1, "sku2");

    $orderTrackingSnippet = $orderTracking->buildOrderTracking();

    $this->assertXmlStringEqualsXmlString(
      "<div id=\"faslet-ot-container\" class=\"faslet-ot-container\">
    <script src=\"https://widget.prod.faslet.net/faslet-orders.js\"></script>
    <script src=\"https://www.googletagmanager.com/gtag/js?id=G-6J8TML143D\" async=\"async\"></script>
    <script>
    window._faslet_orders.configure();
    window._faslet_orders.event(\"widget_order_track\", \"test shop id\", {\"sku\":\"sku1\",\"correlationId\":\"product-1\",\"title\":\"Jacket\",\"variant_id\":\"variant-1-1\",\"variant\":\"Medium\/Blue\",\"price\":400,\"quantity\":2,\"order\":\"order-123\",\"payment_status\":\"paid\"});
    window._faslet_orders.event(\"widget_order_track\", \"test shop id\", {\"sku\":\"sku2\",\"correlationId\":\"product-2\",\"title\":\"T-Shirt\",\"variant_id\":\"variant-2-1\",\"variant\":\"Medium\/Blue\",\"price\":100,\"quantity\":1,\"order\":\"order-123\",\"payment_status\":\"paid\"});
</script>
</div>",
      $orderTrackingSnippet
    );
  }
}
