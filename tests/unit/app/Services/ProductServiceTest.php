<?php

namespace tests\unit\app\Services;

use App\Models\External\ProductModel;
use App\Repositories\ProductRepository;
use App\Services\AbstractDatasourceService;
use App\Services\JSONMapperService;
use App\Services\ProductService;
use Mockery\Mock;

class ProductServiceTest extends \TestCase
{
    public function testServiceIsDatasourceService()
    {
        $productService = app()->make(ProductService::class);

        self::assertInstanceOf(AbstractDatasourceService::class, $productService);
    }

    public function testGetProductById()
    {
        $service = $this->makeService();

        /** @var ProductRepository|Mock $repository */
        $repository = $service->getRepository();

        $fakeId      = 'some-product-id-24-04';
        $fakeProduct = new ProductModel;

        $repository->shouldReceive('getProductById')
            ->once()
            ->with($fakeId)
            ->andReturn($fakeProduct);

        $product = $service->getProductById($fakeId);

        self::assertSame($fakeProduct, $product);
    }

    private function makeService()
    {
        /** @var ProductRepository|Mock $repository */
        $repository = \Mockery::mock(ProductRepository::class)->makePartial();
        $mapper     = app()->make(JSONMapperService::class);

        return new ProductService($repository, $mapper);
    }
}
