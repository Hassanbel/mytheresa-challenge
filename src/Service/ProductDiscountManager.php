<?php

namespace App\Service;

use App\Entity\Product;

class ProductDiscountManager
{
    private DiscountCalculatorInterface $discountCalculator;

    public function __construct(DiscountCalculatorInterface $discountCalculator)
    {
        $this->discountCalculator = $discountCalculator;
    }

    /**
     * @param Product $product
     * @return Product
     */
    public function applyDiscount(Product $product): Product
    {
        if ($this->discountCalculator->supportsDiscountConfiguration($product->getSku())
            || $this->discountCalculator->supportsDiscountConfiguration($product->getCategory())) {
            $this->discountCalculator->calculateDiscount($product);
        } else {
            $product->setPriceDiscountRecap([
                "original" => $product->getPrice(),
                "final" => $product->getPrice(),
                "discount_percentage" => null,
                "currency" => Product::CURRENCY
            ]);
        }

        return $product;
    }
}
