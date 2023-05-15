<?php
namespace App\Repository;

use App\Entity\Product;

interface ProductRepositoryInterface {
    public function searchProducts(?array $params): array;
}
