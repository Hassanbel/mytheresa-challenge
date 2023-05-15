<?php

namespace App\Repository\Fake;

use App\Entity\Product;
use App\Repository\ProductRepositoryInterface;

#[When(env: 'test')]
class ProductRepository implements ProductRepositoryInterface
{
    public function searchProducts(?array $params): array
    {
        $data = [
            0 => [
                'sku' => '000001',
                'name' => 'V Lean leather ankle boots',
                'category' => 'boots',
                'price' => 89000
            ],
            1 => [
                'sku' => '000002',
                'name' => 'BV Lean leather ankle boots',
                'category' => 'boots',
                'price' => 99000
            ],
            2 => [
                'sku' => '000003',
                'name' => 'Ashlington leather ankle boots',
                'category' => 'boots',
                'price' => 71000
            ],
            3 => [
                'sku' => '000004',
                'name' => 'Naima embellished suede sandals',
                'category' => 'sandals',
                'price' => 79500
            ],
            4 => [
                'sku' => '000005',
                'name' => 'Nathane leather sneakers',
                'category' => 'sneakers',
                'price' => 59000
            ],
        ];

        $results = [];

        foreach ($data as $item) {
            $product = new Product();
            $product->setSku($item['sku'])
                ->setName($item['name'])
                ->setCategory($item['category'])
                ->setPrice($item['price']);

            $results[] = $product;
        }

        return $results;
    }
}
