<?php

namespace App\Models\Discounts;

use App\Models\Order\OrderDiscountModel;

/**
 * Class GoldCustomerDiscount
 * @package App\Models\Discounts
 */
class GoldCustomerDiscount extends AbstractDiscount
{
    /** @var string $name */
    protected $name = '-10% loyalty discount';

    /** @var string $code */
    protected $code = 'GCD';

    /**
     * @return bool
     */
    public function isOrderEligible()
    {
        if ($this->order->getCustomer()->getRevenue() + $this->order->getTotal()->getDiscountedValue() >= 1000) {
            return true;
        }

        return false;
    }

    /**
     * @return OrderDiscountModel[]
     */
    public function getApplicableDiscountItems()
    {
        return [ $this->buildDiscountItem(.1 * $this->order->getTotal()->getDiscountedValue(), 1) ];
    }
}
