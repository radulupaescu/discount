<?php

namespace tests\unit\app\Repositories;

use App\Exception\ExceptionCodes;
use App\Exceptions\Customer\CustomerRepositoryException;
use App\Models\External\CustomerModel;
use App\Repositories\CustomerRepository;
use App\Services\JSONMapperService;
use DavidHoeck\LaravelJsonMapper\Exceptions\JsonMapperException;
use Mockery\Mock;

class CustomerRepositoryTest extends \TestCase
{
    /** @var JSONMapperService|Mock $fakeMapper*/
    private $fakeMapper;

    /** @var CustomerRepository $repository */
    private $repository;

    public function setUp()
    {
        $this->fakeMapper = \Mockery::mock(JSONMapperService::class)->makePartial();

        $this->repository = new CustomerRepository;
        $this->repository->setMapper($this->fakeMapper);
    }

    public function testCallingGetCustomerById()
    {
        $fakeCustomer = new CustomerModel;

        $this->fakeMapper->shouldReceive('getMapper')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->fakeMapper->shouldReceive('map')
            ->withAnyArgs()
            ->once()
            ->andReturn($fakeCustomer);

        $fakeCustomerId = 2004;
        $this->repository->setCustomers([$fakeCustomerId => '{"a": "b"}']);

        try {
            $customer = $this->repository->getCustomerById($fakeCustomerId);
            self::assertSame($fakeCustomer, $customer);
        } catch (CustomerRepositoryException $cre) {
            self::fail('should not be here...');
        }
    }

    public function testCallingGetCustomerByIdWithInvalidId()
    {
        $this->fakeMapper->shouldReceive('getMapper')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->fakeMapper->shouldReceive('map')
            ->withAnyArgs()
            ->once()
            ->andThrow(new JsonMapperException('mock exception'));

        $fakeCustomerId = 2004;
        $this->repository->setCustomers([$fakeCustomerId => '{"a": "b"}']);

        try {
            $notImportant = $this->repository->getCustomerById($fakeCustomerId);

            self::fail('should throw error');
        } catch (CustomerRepositoryException $cre) {
            self::assertSame(ExceptionCodes::INVALID_REPOSITORY_RESPONSE, $cre->getCustomCode());
            self::assertEquals('Invalid customer JSON for customer id: ' . $fakeCustomerId, $cre->getMessage());
        } catch (\Exception $e) {
            self::fail('should have specific exception');
        }
    }
}
