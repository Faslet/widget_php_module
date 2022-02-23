<?php

declare(strict_types=1);

namespace Faslet\Tests;

use Faslet\MissingParameterException;
use PHPUnit\Framework\TestCase;

final class WidgetTest extends TestCase
{
    public function testSetsShopIdOnConstruction(): void
    {
        $testShopId = 'test shop id';
        $widget = new \Faslet\Widget($testShopId);
        $this->assertEquals($widget->getShopId(), $testShopId);
    }

    public function testReturnsValidScriptTag(): void
    {
        $testShopId = 'test shop id';
        $widget = new \Faslet\Widget($testShopId);
        $script = $widget->buildScriptTag();

        $this->assertEqualsIgnoringCase(
            "<script src=\"https://widget.prod.faslet.net/faslet-app.min.js\" defer></script>",
            $script
        );
    }

    public function testReturnsAValidWidgetTag(): void
    {
        $testShopId = 'test shop id';
        $widget = new \Faslet\Widget($testShopId);

        $widget
            ->withBrand('test brand')
            ->withProductName('test product name')
            ->withFasletProductTag('Faslet_Test_Male')
            ->withProductImage('https://placekitten.com/4')
            ->withProductId("product id")
            ->withLocale("en")
            ->withUrl("https://shop.com/");

        $widget
            ->addColor("red", "Magnificent Red")
            ->addColor("blue", "Dashing Blue");


        $widget
            ->addVariant("var_1", "S", true, "sku_1", "red")
            ->addVariant("var_2", "S", true, "sku_2", "blue")
            ->addVariant("var_3", "M", false, "sku_3", "red")
            ->addVariant("var_4", "M", true, "sku_4", "blue");

        $widgetSnippet = $widget
            ->buildWidget();

        $this->assertXmlStringEqualsXmlString(
            "<div id=\"faslet-container\" class=\"faslet-container\">
<script>
    window._faslet = window._faslet || {};
    window._faslet.id = \"product id\";
    window._faslet.variants = [{\"size\":\"S\",\"id\":\"var_1\",\"sku\":\"sku_1\",\"available\":true,\"color\":\"red\"},{\"size\":\"S\",\"id\":\"var_2\",\"sku\":\"sku_2\",\"available\":true,\"color\":\"blue\"},{\"size\":\"M\",\"id\":\"var_3\",\"sku\":\"sku_3\",\"available\":false,\"color\":\"red\"},{\"size\":\"M\",\"id\":\"var_4\",\"sku\":\"sku_4\",\"available\":true,\"color\":\"blue\"}];
    window._faslet.colors = [{\"id\":\"red\",\"name\":\"Magnificent Red\"},{\"id\":\"blue\",\"name\":\"Dashing Blue\"}];
    window._faslet.shopUrl = \"https://shop.com/\";
</script>
<faslet-app
    shop-id=\"$testShopId\"
    platform=\"unknown\"
    product-name=\"test product name\"
    categories=\"Faslet_Test_Male\"
    brand=\"test brand\"
    product-img=\"https://placekitten.com/4\"
    locale=\"en\"
></faslet-app>
</div>",
            $widgetSnippet
        );
    }

    public function testThrowsErrorWhenBrandIsMissing(): void
    {
        $widget = new \Faslet\Widget('test shop id');

        $widget
            ->withProductName('test product name')
            ->withFasletProductTag('Faslet_Test_Male')
            ->withProductImage('https://placekitten.com/4')
            ->withProductId("product id")
            ->withLocale("en")
            ->withUrl("https://shop.com/");

        $this->expectException(MissingParameterException::class);
        $widget->buildWidget();
    }

    public function testThrowsErrorWhenShopIdIsMissing(): void
    {
        $widget = new \Faslet\Widget("");

        $widget
            ->withBrand('test brand')
            ->withProductName('test product name')
            ->withFasletProductTag('Faslet_Test_Male')
            ->withProductImage('https://placekitten.com/4')
            ->withProductId("product id")
            ->withLocale("en")
            ->withUrl("https://shop.com/");

        $this->expectException(MissingParameterException::class);
        $widget->buildWidget();
    }


    public function testThrowsErrorWhenVariantsAreMissing(): void
    {
        $widget = new \Faslet\Widget('test shop id');

        $widget
            ->withBrand('test brand')
            ->withProductName('test product name')
            ->withFasletProductTag('Faslet_Test_Male')
            ->withProductImage('https://placekitten.com/4')
            ->withProductId("product id")
            ->withLocale("en")
            ->withUrl("https://shop.com/");

        $this->expectException(MissingParameterException::class);
        $widget->buildWidget();
    }
}
