<?php

namespace tests\unit\app\Services;

use App\Models\External\CustomerModel;
use App\Repositories\CustomerRepository;
use App\Services\AbstractDatasourceService;
use App\Services\CustomerService;
use App\Services\JSONMapperService;
use Mockery\Mock;

class CustomerServiceTest extends \TestCase
{
    public function testServiceIsDatasourceService()
    {
        $customerService = app()->make(CustomerService::class);

        self::assertInstanceOf(AbstractDatasourceService::class, $customerService);
    }

    public function testGetCustomerById()
    {
        $service = $this->makeService();

        /** @var CustomerRepository|Mock $repository */
        $repository = $service->getRepository();

        $fakeId      = 2004;
        $fakeCusomer = new CustomerModel;

        $repository->shouldReceive('getCustomerById')
            ->once()
            ->with($fakeId)
            ->andReturn($fakeCusomer);

        $customer = $service->getCustomerById($fakeId);

        self::assertSame($fakeCusomer, $customer);
    }

    private function makeService()
    {
        /** @var CustomerRepository|Mock $repository */
        $repository = \Mockery::mock(CustomerRepository::class)->makePartial();
        $mapper     = app()->make(JSONMapperService::class);

        return new CustomerService($repository, $mapper);
    }
}
