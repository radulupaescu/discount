<?php

namespace tests\unit\app\Providers;

use App\Services\DiscountService;

class DiscountServiceProviderTest extends \TestCase
{
    /**
     * registering the provider will add the CustomerService
     * into the service container
     */
    public function testRegisteringTheProviderWillAddTheMemoryService()
    {
        $discountService1 = app()->make(DiscountService::class);
        $discountService2 = app()->make(DiscountService::class);

        self::assertInstanceOf(DiscountService::class, $discountService1);

        self::assertSame($discountService1, $discountService2);
    }
}
