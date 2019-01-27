<?php

namespace tests\unit\app\Providers;

use App\Services\ProductService;

class ProductServiceProviderTest extends \TestCase
{
    /**
     * registering the provider will add the CustomerService
     * into the service container
     */
    public function testRegisteringTheProviderWillAddTheMemoryService()
    {
        $productService1 = app()->make(ProductService::class);
        $productService2 = app()->make(ProductService::class);

        self::assertInstanceOf(ProductService::class, $productService1);

        self::assertSame($productService1, $productService2);
    }
}
