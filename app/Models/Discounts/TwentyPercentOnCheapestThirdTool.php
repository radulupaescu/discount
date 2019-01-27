<?php

namespace App\Models\Discounts;

use App\Models\Order\OrderDiscountModel;
use App\Models\Order\OrderModel;
use App\Models\Order\OrderProductModel;

/**
 * Class TwentyPercentOnCheapestThirdTool
 * @package App\Models\Discounts
 */
class TwentyPercentOnCheapestThirdTool extends AbstractDiscount
{
    /** @var string $name */
    protected $name = '-20% for third tool';

    /** @var string $code */
    protected $code = 'TP3T';

    /** @var int $productCategory */
    private $productCategory = 1;

    /** @var OrderProductModel */
    private $cheapest = null;

    /**
     * @return bool
     */
    public function isOrderEligible()
    {
        if ($this->getEligibleItem($this->order) !== null) {
            return true;
        }

        return false;
    }

    /**
     * @return OrderDiscountModel[]
     */
    public function getApplicableDiscountItems()
    {
        return [ $this->buildDiscountItem(.2 * $this->cheapest->getUnitPrice(), 1) ];
    }

    /**
     * @param OrderModel $order
     *
     * @return OrderProductModel|null
     */
    private function getEligibleItem(OrderModel $order)
    {
        /** @var OrderProductModel[] $orderItems */
        $orderItems = $order->getItems();
        $tools      = 0;

        /** @var OrderProductModel $cheapest */
        $cheapest = null;

        foreach ($orderItems as $item) {
            if ($item->getProduct()->getCategory() == $this->productCategory) {
                $tools += $item->getQuantity();

                if ($cheapest == null || $cheapest->getUnitPrice() > $item->getUnitPrice()) {
                    $cheapest = $item;
                }
            }
        }

        if ($tools < 3) {
            $cheapest = null;
        }

        $this->cheapest = $cheapest;

        return $cheapest;
    }
}
