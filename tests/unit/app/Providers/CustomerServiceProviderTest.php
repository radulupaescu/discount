<?php

namespace tests\unit\app\Providers;

use App\Services\CustomerService;

class CustomerServiceProviderTest extends \TestCase
{
    /**
     * registering the provider will add the CustomerService
     * into the service container
     */
    public function testRegisteringTheProviderWillAddTheMemoryService()
    {
        $customerService1 = app()->make(CustomerService::class);
        $customerService2 = app()->make(CustomerService::class);

        self::assertInstanceOf(CustomerService::class, $customerService1);

        self::assertSame($customerService1, $customerService2);
    }
}
