<?php

namespace App\Service;

use App\Entity\Product;

interface DiscountCalculatorInterface
{
    public function supportsDiscountConfiguration(string $type): bool;

    public function calculateDiscount(Product $product): Product;
}
