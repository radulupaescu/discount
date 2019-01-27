<?php

namespace App\Exceptions\Order;

use App\Exception\ExceptionCodes;
use App\Exceptions\BaseDiscountServiceException;

class OrderParserException extends BaseDiscountServiceException
{
    /**
     * @param string $field
     *
     * @return OrderParserException
     */
    public static function missingInformation(string $field)
    {
        return new self('Incomplete order data. Field ' . $field . ' is missing.' , ExceptionCodes::MISSING_ORDER_FIELDS);
    }

    /**
     * @param string     $productId
     * @param \Throwable $previous
     *
     * @return OrderParserException
     */
    public static function orderProductNotFound(string $productId, \Throwable $previous)
    {
        return new self('Invalid product-id found while parsing order: ' . $productId , ExceptionCodes::INVALID_PRODUCT_ID, $previous);
    }

    /**
     * @param string     $productId
     * @param \Throwable $previous
     *
     * @return OrderParserException
     */
    public static function orderCustomerNotFound(string $productId, \Throwable $previous)
    {
        return new self('Invalid customer-id found while parsing order: ' . $productId , ExceptionCodes::INVALID_CUSTOMER_ID, $previous);
    }
}
