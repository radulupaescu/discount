<?php

namespace App\Models\Order;

use App\Contracts\OrderItem;
use App\Models\External\CustomerModel;

/**
 * Class OrderModel
 * @package App\Models
 */
class OrderModel
{
    /** @var integer $id */
    private $id;

    /** @var CustomerModel $customer */
    private $customer;

    /** @var OrderItem[] $items */
    private $items;

    /** @var OrderItem[] $discountItems */
    private $discountItems;

    /** @var OrderTotalModel $total */
    private $total;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     *
     * @return OrderModel
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return CustomerModel
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param CustomerModel $customer
     *
     * @return OrderModel
     */
    public function setCustomer(CustomerModel $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return OrderItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param OrderItem[] $items
     *
     * @return OrderModel
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @param OrderItem $item
     *
     * @return $this
     */
    public function addItem(OrderItem $item)
    {
        $hash         = spl_object_hash($item);
        $items        = $this->getItems();
        $items[$hash] = $item;

        $this->setItems($items);

        return $this;
    }

    /**
     * @param $itemKey
     *
     * @return OrderItem
     */
    public function getItem($itemKey)
    {
        return $this->items[$itemKey];
    }

    /**
     * @return OrderItem[]
     */
    public function getDiscountItems()
    {
        return $this->discountItems;
    }

    /**
     * @param OrderItem[] $discountItems
     *
     * @return OrderModel
     */
    public function setDiscountItems($discountItems)
    {
        $this->discountItems = $discountItems;

        return $this;
    }

    /**
     * @return OrderTotalModel
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param OrderTotalModel $total
     *
     * @return OrderModel
     */
    public function setTotal(OrderTotalModel $total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return OrderModel
     */
    public function applyDiscounts()
    {
        $discount = 0;

        foreach ($this->getDiscountItems() as $item) {
            $discount += $item->getTotal();
        }

        $this->setTotal(
            $this->getTotal()
                ->setDiscount($discount)
                ->makeDiscountedValue()
        );

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $orderArray = [
            'id'               => $this->getId(),
            'customer-id'      => $this->getCustomer()->getId(),
            'items'            => [],
            'discounted-items' => [],
            'total'            => $this->getTotal()->getOriginalValue(),
            'discount'         => $this->getTotal()->getDiscount(),
            'discounted-total' => $this->getTotal()->getDiscountedValue()
        ];

        foreach ($this->getItems() as $item) {
            $orderArray['items'][] = $item->toArray();
        }

        foreach ($this->getDiscountItems() as $item) {
            $orderArray['discounted-items'][] = $item->toArray();
        }

        return $orderArray;
    }
}
