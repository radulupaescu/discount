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
    public function setQuantity(int $quantity);

    /**
     * @return float
     */
    public function getUnitPrice();

    /**
     * @param float $unitPrice
     *
     * @return OrderItem
     */
    public function setUnitPrice(float $unitPrice);

    /**
     * @return float
     */
    public function getTotal();

    /**
     * @param float $total
     *
     * @return OrderItem
     */
    public function setTotal(float $total);

    /**
     * @return OrderItem
     */
    public function updateTotal();

    /**
     * @return array
     */
    public function toArray();
}
