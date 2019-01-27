<?php

namespace App\Services;

use App\Contracts\Repositories\CustomerRepository;
use App\Exceptions\Customer\CustomerRepositoryException;
use App\Models\External\CustomerModel;

/**
 * Class CustomerService
 * @package App\Services
 *
 * @property CustomerRepository $repository
 */
class CustomerService extends AbstractDatasourceService
{
    /**
     * @param int $id
     *
     * @return CustomerModel
     * @throws CustomerRepositoryException
     */
    public function getCustomerById($id)
    {
        return $this->repository->getCustomerById($id);
    }
}
