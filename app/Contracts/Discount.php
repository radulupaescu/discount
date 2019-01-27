<?php

namespace App\Contracts;

use App\Models\Order\OrderDiscountModel;
use App\Models\Order\OrderModel;

/**
 * Interface Discount
 * @package App\Contracts
 */
interface Discount
{
    /**
     * @return bool
     */
    public function isOrderEligible();

    /**
     * @return OrderDiscountModel[]
     */
    public function getApplicableDiscountItems();
}
