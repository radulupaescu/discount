<?php

namespace App\Exceptions\Order;

use App\Exception\ExceptionCodes;
use App\Exceptions\BaseDiscountServiceException;

/**
 * Class OrderParserException
 * @package App\Exceptions\Order
 */
class OrderParserException extends BaseDiscountServiceException
{
    /**
     * @param string $field
     *
     * @return OrderParserException
     */
    public static function missingInformation($field)
    {
        return new self('Incomplete order data. Field ' . $field . ' is missing.' , ExceptionCodes::MISSING_ORDER_FIELDS);
    }

    /**
     * @param string     $productId
     * @param \Throwable $previous
     *
     * @return OrderParserException
     */
    public static function orderProductNotFound($productId, $previous)
    {
        return new self('Invalid product-id found while parsing order: ' . $productId , ExceptionCodes::INVALID_PRODUCT_ID, $previous);
    }

    /**
     * @param string     $productId
     * @param \Throwable $previous
     *
     * @return OrderParserException
     */
    public static function orderCustomerNotFound($productId, $previous)
    {
        return new self('Invalid customer-id found while parsing order: ' . $productId , ExceptionCodes::INVALID_CUSTOMER_ID, $previous);
    }
}
