<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    private const LIMIT = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchProducts(?array $params): array
    {
        $results = [];

        $products = $this->findAll();

        /**
         * @var Product $product
         */
        foreach ($products as $product) {

            if (!empty($params[0]) && $product->getCategory() !== $params[0]) {
                continue;
            }

            if ($params[1] !== 0 && $product->getPrice() > $params[1]) {
                continue;
            }

            $results[] = $product;

            if (count($results) > self::LIMIT) {
                break;
            }
        }

        return $results;
    }
}
