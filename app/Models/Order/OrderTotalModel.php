<?php

namespace App\Models\Order;

/**
 * Class OrderTotalModel
 * @package App\Models\Order
 */
class OrderTotalModel
{
    /** @var float $originalValue */
    private $originalValue;

    /** @var float $discount */
    private $discount;

    /** @var float $discountedValue */
    private $discountedValue;

    /**
     * @return float
     */
    public function getOriginalValue()
    {
        return $this->originalValue;
    }

    /**
     * @param float $originalValue
     *
     * @return OrderTotalModel
     */
    public function setOriginalValue(float $originalValue)
    {
        $this->originalValue = $originalValue;

        return $this;
    }

    /**
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     *
     * @return OrderTotalModel
     */
    public function setDiscount(float $discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return float
     */
    public function getDiscountedValue()
    {
        return $this->discountedValue;
    }

    /**
     * @param float $discountedValue
     *
     * @return OrderTotalModel
     */
    public function setDiscountedValue(float $discountedValue)
    {
        $this->discountedValue = $discountedValue;

        return $this;
    }

    /**
     * @return OrderTotalModel
     */
    public function makeDiscountedValue()
    {
        return $this->setDiscountedValue($this->getOriginalValue() + $this->getDiscount());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'original-value'   => $this->getOriginalValue(),
            'discount'         => $this->getDiscount(),
            'discounted-value' => $this->getDiscountedValue()
        ];
    }
}
