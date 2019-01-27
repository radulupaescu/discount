<?php

namespace App\Contracts\Repositories;

use App\Exceptions\Customer\CustomerRepositoryException;
use App\Models\External\CustomerModel;

interface CustomerRepository
{
    /**
     * @param int $id
     *
     * @return CustomerModel
     * @throws CustomerRepositoryException
     */
    public function getCustomerById($id);
}
