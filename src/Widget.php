<?php

declare(strict_types=1);

namespace Faslet;

use DOMDocument;

final class Widget
{
  private $shopId;
  private $platform = 'unknown';
  private $productName;
  private $productBrand;
  private $productImageUrl;
  private $productIdentifier;
  private $productTag;
  private $locale;

  public function __construct(string $shopId)
  {
    $this->shopId = $shopId;
  }

  public function getShopId()
  {
    return $this->shopId;
  }

  public function getProductName()
  {
    return $this->productName;
  }

  public function getProductBrand()
  {
    return $this->productBrand;
  }

  public function getProductImageUrl()
  {
    return $this->productImageUrl;
  }

  public function getProductIdentifier()
  {
    return $this->productIdentifier;
  }

  public function getFasletProductTag()
  {
    return $this->productTag;
  }
  public function withProductName(string $productName)
  {
    $this->productName = $productName;
    return $this;
  }

  public function withBrand(string $productBrand)
  {
    $this->productBrand = $productBrand;
    return $this;
  }

  public function withImage(string $productImageUrl)
  {
    $this->productImageUrl = $productImageUrl;
    return $this;
  }

  public function withProductId(string $productIdentifier)
  {
    $this->productIdentifier = $productIdentifier;
    return $this;
  }

  public function withFasletProductTag(string $productTag)
  {
    $this->productTag = $productTag;
    return $this;
  }

  public function withLocale(string $locale)
  {
    $this->locale = $locale;
    return $this;
  }

  public function buildScriptTag(): string
  {
    $doc = new DOMDocument();

    $scriptTag = $doc->createElement("script");
    $scriptTag->setAttribute("src", "https://widget.prod.faslet.net/faslet-app.min.js");
    $scriptTag->setAttribute("defer", "defer");
    $doc->formatOutput = true;
    return $doc->saveHTML($scriptTag);
  }

  public function buildWidget(): string
  {
    $variants = "";
    $colors = "";
    $shopPageUrl = "";

    $doc = new DOMDocument();

    $container = $doc->createElement('div');
    $container->setAttribute("class", "faslet-container");

    $metaInfoScript = $doc->createElement("script");
    $metaInfoScript->textContent = "
    window._faslet = window._faslet || {};
    window._faslet.id = \"$this->productIdentifier\",
    window._faslet.variants = $variants;
    window._faslet.shopUrl = \"$shopPageUrl\";
    window._faslet.colors = $colors;\n";

    $container->appendChild($metaInfoScript);

    $fasletTag = $doc->createElement('faslet-app');
    $fasletTag->setAttribute("platform", $this->platform);
    if (isset($this->productTag)) {
      $fasletTag->setAttribute("categories",  $this->productTag);
    }
    $fasletTag->setAttribute("product-name", $this->productName);
    $fasletTag->setAttribute("shop-id", $this->shopId);
    $fasletTag->setAttribute("brand", $this->productBrand);
    if (isset($this->locale)) {
      $fasletTag->setAttribute("locale", $this->locale);
    }
    $fasletTag->setAttribute("product-img", $this->productImageUrl);

    $container->appendChild($fasletTag);

    $doc->formatOutput = true;

    return $doc->saveHTML($container);
  }
}
