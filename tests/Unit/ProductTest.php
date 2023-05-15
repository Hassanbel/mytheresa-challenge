<?php

namespace App\Tests\Unit;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = new Product();
    }

    public function testGetSku(): void
    {
        $value = '000002';

        $response = $this->product->setSku($value);
        $getSku = $this->product->getSku();

        self::assertInstanceOf(Product::class, $response);
        self::assertEquals($value, $getSku);
        self::assertEquals($value, $this->product->getSku());
    }

    public function testGetName(): void
    {
        $value = 'BV Lean leather ankle boots';

        $response = $this->product->setName($value);
        $getName = $this->product->getName();

        self::assertInstanceOf(Product::class, $response);
        self::assertEquals($value, $getName);
        self::assertEquals($value, $this->product->getName());
    }

    public function testGetCategory(): void
    {
        $value = 'boots';

        $response = $this->product->setCategory($value);

        self::assertInstanceOf(Product::class, $response);
        self::assertEquals($value, $this->product->getCategory());
    }

    public function testGetPrice(): void
    {
        $value = '99000';

        $response = $this->product->setPrice($value);

        self::assertInstanceOf(Product::class, $response);
        self::assertEquals($value, $this->product->getPrice());
    }

    public function testGetPriceDiscountRecap(): void
    {
        $value = [
            "original" => $this->product->getPrice(),
            "final" => $this->product->getPrice(),
            "discount_percentage" => "30%",
            "currency" => "EUR"
        ];

        $response = $this->product->setPriceDiscountRecap($value);

        self::assertInstanceOf(Product::class, $response);
        self::assertEquals($value, $this->product->getPriceDiscountRecap());
    }
}
