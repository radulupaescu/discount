<?php

namespace tests\unit\app\Models\Order;

use App\Models\Order\OrderTotalModel;

class OrderTotalModelTest extends \TestCase
{
    public function testGettersAndSettersSetDataCorrectly()
    {
        $mockOrderTotal = [
            'originalValue'   => 99.99,
            'discount'        => -19.99,
            'discountedValue' => 80
        ];

        $orderTotal = new OrderTotalModel;

        $orderTotal->setOriginalValue($mockOrderTotal['originalValue'])
            ->setDiscount($mockOrderTotal['discount'])
            ->setDiscountedValue($mockOrderTotal['discountedValue']);

        self::assertEquals($mockOrderTotal['originalValue'], $orderTotal->getOriginalValue());
        self::assertEquals($mockOrderTotal['discount'], $orderTotal->getDiscount());
        self::assertEquals($mockOrderTotal['discountedValue'], $orderTotal->getDiscountedValue());
    }

    public function testMakeDiscountedValue()
    {
        $mockOrderTotal = [
            'originalValue' => 99.99,
            'discount'      => -19.99
        ];

        $expectedValue = 80;

        $orderTotal = new OrderTotalModel;

        $orderTotal->setOriginalValue($mockOrderTotal['originalValue'])
            ->setDiscount($mockOrderTotal['discount']);

        $orderTotal->makeDiscountedValue();

        self::assertEquals($expectedValue, $orderTotal->getDiscountedValue());
    }

    public function testToArray()
    {
        $mockOrderTotal = [
            'originalValue'   => 99.99,
            'discount'        => 19.99,
            'discountedValue' => 80
        ];

        $expectedValue = [
            'original-value'   => $mockOrderTotal['originalValue'],
            'discount'         => $mockOrderTotal['discount'],
            'discounted-value' => $mockOrderTotal['discountedValue'],
        ];

        $orderTotal = new OrderTotalModel;

        $orderTotal->setOriginalValue($mockOrderTotal['originalValue'])
            ->setDiscount($mockOrderTotal['discount'])
            ->setDiscountedValue($mockOrderTotal['discountedValue']);

        self::assertEquals($expectedValue, $orderTotal->toArray());
    }
}
