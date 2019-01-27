<?php

namespace tests\unit\app\Repositories;

use App\Exception\ExceptionCodes;
use App\Exceptions\Product\ProductRepositoryException;
use App\Models\External\ProductModel;
use App\Repositories\ProductRepository;
use App\Services\JSONMapperService;
use DavidHoeck\LaravelJsonMapper\Exceptions\JsonMapperException;
use Mockery\Mock;

class ProductRepositoryTest extends \TestCase
{
    /** @var JSONMapperService|Mock $fakeMapper*/
    private $fakeMapper;

    /** @var ProductRepository $repository */
    private $repository;

    public function setUp()
    {
        $this->fakeMapper = \Mockery::mock(JSONMapperService::class)->makePartial();

        $this->repository = new ProductRepository;
        $this->repository->setMapper($this->fakeMapper);
    }

    public function testCallingGetProductById()
    {
        $fakeProduct = new ProductModel;

        $this->fakeMapper->shouldReceive('getMapper')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->fakeMapper->shouldReceive('map')
            ->withAnyArgs()
            ->once()
            ->andReturn($fakeProduct);

        $fakeProductId = 'product-id';
        $this->repository->setProducts([$fakeProductId => '{"a": "b"}']);

        try {
            $product = $this->repository->getProductById($fakeProductId);
            self::assertSame($fakeProduct, $product);
        } catch (ProductRepositoryException $pre) {
            self::fail('should not be here...');
        }
    }

    public function testCallingGetProductByIdWithInvalidId()
    {
        $this->fakeMapper->shouldReceive('getMapper')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->fakeMapper->shouldReceive('map')
            ->withAnyArgs()
            ->once()
            ->andThrow(new JsonMapperException('mock exception'));

        $fakeProductId = 'product-id';
        $this->repository->setProducts([$fakeProductId => '{"a": "b"}']);

        try {
            $notImportant = $this->repository->getProductById($fakeProductId);

            self::fail('should throw error');
        } catch (ProductRepositoryException $cre) {
            self::assertSame(ExceptionCodes::INVALID_PRODUCT_ID, $cre->getCustomCode());
            self::assertEquals('Invalid product id, for id: ' . $fakeProductId, $cre->getMessage());
        } catch (\Exception $e) {
            self::fail('should have specific exception');
        }
    }
}
