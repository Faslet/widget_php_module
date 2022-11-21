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
    private $shopPageUrl;
    private $colors = array();
    private $variants = array();
    private $addToCartSnippet;

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

    public function withProductImage(string $productImageUrl)
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

    public function withUrl(string $shopPageUrl)
    {
        $this->shopPageUrl = $shopPageUrl;
        return $this;
    }

    public function withAddToCartSnippet(string $addToCartSnippet)
    {
        $this->addToCartSnippet = $addToCartSnippet;
        return $this;
    }

    public function withAddToCartRedirect(string $urlWithVariantId, string $toReplace = "{VARIANT_ID}")
    {
        $this->addToCartSnippet = "function(id) { 
      window.location.assign(\"$urlWithVariantId\".replace(\"$toReplace\", id));
      return Promise.resolve();
    }";
    }

    public function addColor(string $id, string $name)
    {
        $this->colors[] = array("id" => $id, "name" => $name);
        return $this;
    }

    public function addVariant(string $variantId, string $sizeLabel, bool $inStock, string $sku, string $colorId, int $price, ?string $imageUrl = null)
    {
        $this->variants[] = array("size" => $sizeLabel, "id" => $variantId, "sku" => $sku, "available" => $inStock, "color" => $colorId, "price" => $price);
        if($imageUrl != null) {
            $currentIndex = count($this->variants) - 1;
            $this->variants[$currentIndex]["imageUrl"] = $imageUrl;
        }


        return $this;
    }

    public static function buildScriptTag(): string
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
        $doc = new DOMDocument();

        if (!isset($this->shopId) || $this->shopId == "") {
            throw new \Faslet\MissingParameterException("Shop ID is missing, please construct your Widget instance with your Faslet Shop ID which you can obtain from Faslet");
        }

        if (!isset($this->productBrand) || $this->productBrand == "") {
            throw new \Faslet\MissingParameterException("Brand is missing, please call withBrand on your Widget instance");
        }

        if (!isset($this->productIdentifier) || $this->productIdentifier == "") {
            throw new \Faslet\MissingParameterException("Product Identifier is missing, please call withProductId on your Widget instance");
        }

        if (!isset($this->productName) || $this->productName == "") {
            throw new \Faslet\MissingParameterException("Product Name is missing, please call withProductName on your Widget instance");
        }

        if (!isset($this->productImageUrl) || $this->productImageUrl == "") {
            throw new \Faslet\MissingParameterException("Product Image Url is missing, please call withProductImage on your Widget instance");
        }

        if (count($this->variants) === 0) {
            throw new \Faslet\MissingParameterException("Variants are empty, please call addVariant on your Widget instance");
        }


        $container = $doc->createElement('div');
        $container->setAttribute("class", "faslet-container");
        $container->setAttribute("id", "faslet-container");

        $metaInfoScript = $doc->createElement("script");
        $metaInfoScript->textContent = "\n    window._faslet = window._faslet || {};\n";
        $metaInfoScript->textContent .=  "    window._faslet.id = \"$this->productIdentifier\";\n";

        $flatVariants = json_encode($this->variants);
        $metaInfoScript->textContent .=  "    window._faslet.variants = $flatVariants;\n";

        $flatColors = json_encode($this->colors);
        $metaInfoScript->textContent .=  "    window._faslet.colors = $flatColors;\n";

        if (isset($this->shopPageUrl)) {
            $metaInfoScript->textContent .=  "    window._faslet.shopUrl = \"$this->shopPageUrl\";\n";
        }

        if (isset($this->addToCartSnippet)) {
            $metaInfoScript->textContent .=  "    window._faslet.addToCart = $this->addToCartSnippet;\n";
        }

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
