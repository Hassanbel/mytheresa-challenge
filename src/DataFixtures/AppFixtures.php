<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $productsData = [
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

        for ($i = 0; $i < 5; $i++) {
            $product = new Product();
            $product->setSku($productsData[$i]['sku'])
                ->setName($productsData[$i]['name'])
                ->setCategory($productsData[$i]['category'])
                ->setPrice($productsData[$i]['price']);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
