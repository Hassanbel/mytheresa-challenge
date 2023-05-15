<?php

namespace App\Service;


use App\Entity\Product;
use App\Repository\ProductRepository;

class PercentageDiscountCalculator implements DiscountCalculatorInterface
{
    private const DISCOUNT_ON_CATEGORY = 'boots';
    private const DISCOUNT_ON_SKU = '000001';
    private const DISCOUNT_30 = '30';
    private const DISCOUNT_15 = '15';

    private ProductRepository $productRepo;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function supportsDiscountConfiguration(string $type): bool
    {
        return (in_array($type, [self::DISCOUNT_ON_CATEGORY, self::DISCOUNT_ON_SKU]));
    }

    public function calculateDiscount(Product $product): Product
    {
        $discounts = [];

        if ($product->getCategory() === self::DISCOUNT_ON_CATEGORY) {
            $discounts[] = self::DISCOUNT_30;
        }

        if ($product->getSku() === self::DISCOUNT_ON_SKU) {
            $discounts[] = self::DISCOUNT_15;
        }

        $discountRecap = [
            "original" => $product->getPrice(),
            "final" => ($product->getPrice() - (($product->getPrice() / 100) * max($discounts))),
            "discount_percentage" => max($discounts) . "%",
            "currency" => Product::CURRENCY
        ];

        $product->setPriceDiscountRecap($discountRecap);

        $this->productRepo->save($product);

        return $product;
    }
}
