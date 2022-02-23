<?php

declare(strict_types=1);

namespace Faslet\Tests;

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

    public function testResturnsAValidaWidgetTag(): void
    {
        $testShopId = 'test shop id';
        $widget = new \Faslet\Widget($testShopId);
        $widgetSnippet = $widget
            ->withBrand('test brand')
            ->withProductName('test product name')
            ->withFasletProductTag('Faslet_Test_Male')
            ->withImage('https://placekitten.com/4')
            ->withProductId("product id")
            ->withLocale("en")
            ->buildWidget();

        $this->assertXmlStringEqualsXmlString(
            "<div class=\"faslet-container\">
<script>
    window._faslet = window._faslet || {};
    window._faslet.id = \"product id\",
    window._faslet.variants = ;
    window._faslet.shopUrl = \"\";
    window._faslet.colors = ;
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
}
