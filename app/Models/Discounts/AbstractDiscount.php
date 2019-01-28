<?php

namespace App\Models\Discounts;

use App\Contracts\Discount;
use App\Models\Order\OrderDiscountModel;
use App\Models\Order\OrderModel;

/**
 * Class AbstractDiscount
 * @package App\Models\Discounts
 */
abstract class AbstractDiscount implements Discount
{
    /** @var OrderModel $order */
    protected $order;

    /** @var string $code */
    protected $code = 'N/A';

    /** @var string $name */
    protected $name = 'N/A';

    /**
     * GoldCustomerDiscount constructor.
     *
     * @param OrderModel $order
     */
    public function __construct(OrderModel $order)
    {
        $this->order = $order;
    }

    /**
     * @param float $price
     * @param int   $quantity
     *
     * @return OrderDiscountModel
     */
    protected function buildDiscountItem(float $price, int $quantity)
    {
        $discount = new OrderDiscountModel;

        $discount->setName($this->getName())
            ->setCode($this->getCode())
            ->setUnitPrice($price)
            ->setQuantity($quantity)
            ->updateTotal();

        return $discount;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
