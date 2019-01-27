<?php

namespace App\Exceptions\Customer;

use App\Exception\ExceptionCodes;
use App\Exceptions\BaseDiscountServiceException;

class CustomerRepositoryException extends BaseDiscountServiceException
{
    public static function invalidCustomerId($id)
    {
        return new self('Invalid customer id, for id: ' . $id, ExceptionCodes::INVALID_CUSTOMER_ID);
    }

    public static function invalidRepositoryResponse($id, $previous)
    {
        return new self('Invalid customer JSON for customer id: ' . $id, ExceptionCodes::INVALID_REPOSITORY_RESPONSE, $previous);
    }
}
