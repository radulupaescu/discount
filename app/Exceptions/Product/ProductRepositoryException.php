<?php

namespace App\Exceptions\Product;

use App\Exception\ExceptionCodes;
use App\Exceptions\BaseDiscountServiceException;

/**
 * Class ProductRepositoryException
 * @package App\Exceptions\Product
 */
class ProductRepositoryException extends BaseDiscountServiceException
{
    public static function invalidProductId($id, $previous)
    {
        return new self('Invalid product id, for id: ' . $id, ExceptionCodes::INVALID_PRODUCT_ID, $previous);
    }
}
