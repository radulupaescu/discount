<?php

namespace App\Models\Order;

use App\Contracts\OrderItem;

/**
 * Class AbstractOrderItem
 * @package App\Models\Order
 */
abstract class AbstractOrderItem implements OrderItem
{
    /** @var int $quantity */
    private $quantity;

    /** @var float $unitPrice */
    private $unitPrice;

    /** @var float $total */
    private $total;

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return AbstractOrderItem
     */
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param float $unitPrice
     *
     * @return AbstractOrderItem
     */
    public function setUnitPrice(float $unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param float $total
     *
     * @return AbstractOrderItem
     */
    public function setTotal(float $total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return AbstractOrderItem
     */
    public function updateTotal()
    {
        $this->total = $this->getUnitPrice() * $this->getQuantity();

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'quantity'   => $this->getQuantity(),
            'unit-price' => $this->getUnitPrice(),
            'total'      => $this->getTotal()
        ];
    }
}
