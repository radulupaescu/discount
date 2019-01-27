<?php

namespace App\Models\Discounts;

use App\Contracts\OrderItem;
use App\Models\Order\OrderDiscountModel;
use App\Models\Order\OrderProductModel;

/**
 * Class SixthForFree
 * @package App\Models\Discounts
 */
class SixthForFree extends AbstractDiscount
{
    /** @var string $name */
    protected $name = 'Sixth switch for free';

    /** @var string $code */
    protected $code = 'SFF';

    /** @var int $productCategory */
    private $productCategory = 2;

    /**
     * @return bool
     */
    public function isOrderEligible()
    {
        if (count($this->getEligibleItems()) > 0) {
            return true;
        }

        return false;
    }

    /**
     * @return OrderDiscountModel[]
     */
    public function getApplicableDiscountItems()
    {
        $discountItems = [];
        $itemKeys      = $this->getEligibleItems();

        foreach ($itemKeys as $itemKey) {
            /** @var OrderProductModel $item */
            $item = $this->order->getItem($itemKey);

            $discountItems = array_merge($discountItems, $this->processOrderItem($item));
        }

        return $discountItems;
    }

    /**
     * @param OrderProductModel $item
     *
     * @return OrderItem[]
     */
    private function processOrderItem(OrderProductModel $item)
    {
        $returnableItems = [];

        $discountedItemsCount = $item->getQuantity() / 6;

        if ($item->getQuantity() % 6 == 5) {
            $freeItem = new OrderProductModel;
            $freeItem->setProduct($item->getProduct())
                ->setQuantity(1)
                ->setUnitPrice($item->getUnitPrice())
                ->updateTotal();

            $discountedItemsCount++;
            $returnableItems[] = $freeItem;
        }

        if ($discountedItemsCount > 0) {
            $returnableItems[] = $this->buildDiscountItem($item->getUnitPrice(), $discountedItemsCount);
        }

        return $returnableItems;
    }

    /**
     * @return array
     */
    private function getEligibleItems()
    {
        $eligibleItems = [];

        /** @var OrderProductModel[] $orderItems */
        $orderItems = $this->order->getItems();

        foreach ($orderItems as $key => $item) {
            if ($item->getProduct()->getCategory() == $this->productCategory && $item->getQuantity() > 4) {
                $eligibleItems[] = $key;
            }
        }

        return $eligibleItems;
    }
}
