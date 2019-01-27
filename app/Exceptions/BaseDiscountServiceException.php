<?php

namespace App\Exceptions;

use Throwable;

/**
 * Class BaseDiscountServiceException
 * @package App\Exceptions
 */
class BaseDiscountServiceException extends \Exception
{
    /** @var string $customCode */
    protected $customCode = '';

    /**
     * BaseDiscountServiceException constructor.
     *
     * @param string         $message
     * @param string         $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', string $code, Throwable $previous = null)
    {
        $this->customCode = $code;

        parent::__construct($message, 0, $previous);
    }

    /**
     * @return string
     */
    public function getCustomCode()
    {
        return $this->customCode;
    }
}
