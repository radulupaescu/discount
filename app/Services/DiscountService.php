<?php

namespace App\Services;

use App\Contracts\Discount;
use App\Models\Discounts\GoldCustomerDiscount;
use App\Models\Discounts\SixthForFree;
use App\Models\Discounts\TwentyPercentOnCheapestThirdTool;
use App\Models\Order\OrderDiscountModel;
use App\Models\Order\OrderModel;

/**
 * Class OrderService
 * @package App\Services
 */
class DiscountService
{
    protected $availableDiscounts = [
        TwentyPercentOnCheapestThirdTool::class,
        SixthForFree::class,
        GoldCustomerDiscount::class
    ];

    /**
     * @param OrderModel $order
     *
     * @return OrderModel
     */
    public function applyDiscounts(OrderModel $order)
    {
        return $order->setDiscountItems($this->getOrderDiscountItems($order))
            ->applyDiscounts();
    }

    /**
     * This method compiles a list of discount items to be applied on the order.
     *
     * @param OrderModel $order
     *
     * @return OrderDiscountModel[]
     */
    public function getOrderDiscountItems(OrderModel $order)
    {
        $discountItems = [];

        foreach ($this->availableDiscounts as $discountClass) {
            /** @var Discount $discount */
            $discount = new $discountClass($order);

            if ($discount->isOrderEligible()) {
                $discountItems = array_merge($discountItems, $discount->getApplicableDiscountItems());
            }
        }

        return $discountItems;
    }
}
