<?php

namespace tests\unit\app\Providers;

use App\Services\JSONMapperService;

class JSONMapperServiceProviderTest extends \TestCase
{
    /**
     * registering the provider will add the CustomerService
     * into the service container
     */
    public function testRegisteringTheProviderWillAddTheMemoryService()
    {
        $jsonMapperService1 = app()->make(JSONMapperService::class);
        $jsonMapperService2 = app()->make(JSONMapperService::class);

        self::assertInstanceOf(JSONMapperService::class, $jsonMapperService1);

        self::assertSame($jsonMapperService1, $jsonMapperService2);
    }
}
