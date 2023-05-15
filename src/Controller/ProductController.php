<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\ProductDiscountManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'products', methods: ['GET', 'HEAD'])]
    public function getProducts(Request $request, ProductRepository $productRepo, ProductDiscountManager $discountManager): JsonResponse
    {
        $products = $productRepo->searchProducts(
            [
                $request->query->get("category") ?: "",
                $request->query->get("priceLessThan") ?: 0,
                $request->query->get("page") ?: 1,
            ]
        );

        $jsonResult = [];

        foreach ($products as $product) {
            $discountManager->applyDiscount($product);

            $jsonResult[] = [
                'sku' => $product->getSku(),
                'name' => $product->getName(),
                'category' => $product->getCategory(),
                'price' => $product->getPriceDiscountRecap()
            ];
        }

        return new JsonResponse(json_encode($jsonResult), Response::HTTP_OK, [], true);
    }
}
