<?php

namespace App\Tests\Func;

use App\Controller\ProductController;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new ProductController();
    }

    public function testGetProductControllerResponse()
    {
        $productMock = $this->getProductMock();
        $request = $this->createMock(Request::class);

        $request->expects($this->once())->method('get')->willReturn(new JsonResponse(json_encode(['products' => [$productMock]]), Response::HTTP_OK, [], true));

        $this->assertInstanceOf(JsonResponse::class, $request->get('/products'));
    }

    protected function getProductMock()
    {
        return $this->getMockBuilder(Product::class)
            ->getMock();
    }
}
