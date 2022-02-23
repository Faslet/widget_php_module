<?php

declare(strict_types=1);

namespace Faslet;

use DOMDocument;

final class OrderTracking
{
  private $shopId;
  private $products = array();
  private $orderNumber;
  private $paymentStatus;

  public function __construct(string $shopId)
  {
    $this->shopId = $shopId;
  }

  public function getShopId()
  {
    return $this->shopId;
  }

  public function getOrderNumber()
  {
    return $this->orderNumber;
  }

  public function getPaymentStatus()
  {
    return $this->paymentStatus;
  }

  public function withOrderNumber($orderNumber)
  {
    $this->orderNumber = $orderNumber;
    return $this;
  }

  public function withPaymentStatus($paymentStatus)
  {
    $this->paymentStatus = $paymentStatus;
    return $this;
  }

  public function addProduct($productId, $variantId, $productName, $variantName, $priceTimesQuantity, $quantity, $sku)
  {
    $this->products[] = array("sku" => $sku, "correlationId" => $productId, "title" => $productName, "variant_id" => $variantId, "variant" => $variantName, "price" => $priceTimesQuantity, "quantity" => $quantity);
    return $this;
  }

  public function buildOrderTracking()
  {
    if (!isset($this->shopId) || $this->shopId == "") {
      throw new \Faslet\MissingParameterException("Shop ID is missing, please construct your OrderTracking instance with your Faslet Shop ID which you can obtain from Faslet");
    }

    if (!isset($this->orderNumber) || $this->orderNumber == "") {
      throw new \Faslet\MissingParameterException("Order Number is missing, please call withOrderNumber on your OrderTracking instance");
    }

    if (count($this->products) === 0) {
      throw new \Faslet\MissingParameterException("Products are empty, please call addProduct on your OrderTracking instance");
    }

    $doc = new DOMDocument();

    $container = $doc->createElement('div');
    $container->setAttribute("class", "faslet-ot-container");
    $container->setAttribute("id", "faslet-ot-container");

    $fasletScriptTag = $doc->createElement("script");
    $fasletScriptTag->setAttribute("src", "https://widget.prod.faslet.net/faslet-orders.js");
    $container->appendChild($fasletScriptTag);

    $gaScriptTag = $doc->createElement("script");
    $gaScriptTag->setAttribute("src", "https://www.googletagmanager.com/gtag/js?id=G-6J8TML143D");
    $gaScriptTag->setAttribute("async", "async");
    $container->appendChild($gaScriptTag);

    $trackingScriptTag = $doc->createElement("script");
    $trackingScriptTag->textContent = "\n    window._faslet_orders.configure();\n";
    foreach ($this->products as $orderProduct) {
      $productOrderInfo = $orderProduct;
      $productOrderInfo["order"] = $this->orderNumber;
      $productOrderInfo["payment_status"] = $this->paymentStatus;

      $flatProductOrderInfo = json_encode($productOrderInfo);
      $trackingScriptTag->textContent .= "    window._faslet_orders.event(\"widget_order_track\", \"$this->shopId\", $flatProductOrderInfo);\n";
    }
    $container->appendChild($trackingScriptTag);


    $doc->formatOutput = true;

    return $doc->saveHTML($container);
  }
}
