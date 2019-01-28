<?php

namespace App\Contracts;

/**
 * Interface OrderItem
 * @package App\Contracts
 */
interface OrderItem
{
    /**
     * @return int
     */
    public function getQuantity();

    /**
     * @param int $quantity
     *
     * @return OrderItem
     */
    public function setQuantity($quantity);

    /**
     * @return float
     */
    public function getUnitPrice();

    /**
     * @param float $unitPrice
     *
     * @return OrderItem
     */
    public function setUnitPrice($unitPrice);

    /**
     * @return float
     */
    public function getTotal();

    /**
     * @param float $total
     *
     * @return OrderItem
     */
    public function setTotal($total);

    /**
     * @return OrderItem
     */
    public function updateTotal();

    /**
     * @return array
     */
    public function toArray();
}
