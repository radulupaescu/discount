<?php

namespace App\Repositories;

use App\Contracts\Repositories\CustomerRepository as CustomerRepositoryContract;
use App\Exceptions\Customer\CustomerRepositoryException;
use App\Models\External\CustomerModel;
use DavidHoeck\LaravelJsonMapper\Exceptions\JsonMapperException;

/**
 * Class CustomerRepository
 * @package App\Repositories
 */
class CustomerRepository extends AbstractRepository implements CustomerRepositoryContract
{
    private $customers = [
        1 => '{"id":"1","name":"Coca Cola","since":"2014-06-28","revenue":"492.12"}',
        2 => '{"id":"2","name":"Teamleader","since":"2015-01-15","revenue":"1505.95"}',
        3 => '{"id":"3","name":"Jeroen De Wit","since":"2016-02-11","revenue":"0.00"}'
    ];

    /**
     * @param int $id
     *
     * @return CustomerModel
     * @throws CustomerRepositoryException
     */
    public function getCustomerById($id)
    {
        $fakeCURLresponse = json_decode($this->customers[$id]);

        try {
            /** @var CustomerModel $customer */
            $customer = $this->getMapper()->map($fakeCURLresponse, new CustomerModel);
        } catch (JsonMapperException $e) {
            throw CustomerRepositoryException::invalidRepositoryResponse($id, $e);
        }

        return $customer;
    }

    /**
     * @param array $customers
     */
    public function setCustomers(array $customers)
    {
        $this->customers = $customers;
    }
}
