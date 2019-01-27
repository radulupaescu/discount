<?php

namespace App\Models\Order;

/**
 * Class OrderDiscountModel
 * @package App\Models\Order
 */
class OrderDiscountModel extends AbstractOrderItem
{
    /** @var string $name */
    private $name;

    /** @var string $code */
    private $code;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return OrderDiscountModel
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return OrderDiscountModel
     */
    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param float $price
     *
     * @return AbstractOrderItem
     */
    public function setUnitPrice(float $price)
    {
        return parent::setUnitPrice(-1 * $price);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $orderItem = parent::toArray();

        return array_merge([
            'discount-name' => $this->getName(),
            'discount-code' => $this->getCode()
        ], $orderItem);
    }
}
