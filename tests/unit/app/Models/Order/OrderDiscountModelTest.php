<?php

namespace tests\unit\app\Models\Order;

use App\Models\Order\OrderDiscountModel;

class OrderDiscountModelTest extends \TestCase
{
    public function testGettersAndSettersSetDataCorrectly()
    {
        $mockOrderDiscount = [
            'name'      => 'applied discount name',
            'code'      => 'CDE',
            'quantity'  => 2,
            'unitPrice' => 0.99,
            'total'     => -1.98
        ];

        $orderDiscount = new OrderDiscountModel;

        $orderDiscount->setName($mockOrderDiscount['name'])
            ->setCode($mockOrderDiscount['code'])
            ->setQuantity($mockOrderDiscount['quantity'])
            ->setTotal($mockOrderDiscount['total'])
            ->setUnitPrice($mockOrderDiscount['unitPrice']);

        self::assertEquals($mockOrderDiscount['name'], $orderDiscount->getName());
        self::assertEquals($mockOrderDiscount['code'], $orderDiscount->getCode());
        self::assertEquals($mockOrderDiscount['quantity'], $orderDiscount->getQuantity());
        self::assertEquals(-1 * $mockOrderDiscount['unitPrice'], $orderDiscount->getUnitPrice());
        self::assertEquals($mockOrderDiscount['total'], $orderDiscount->getTotal());
    }

    public function setUnitPriceChangesSign()
    {
        $price = 50;

        $orderDiscount = new OrderDiscountModel;

        $orderDiscount->setUnitPrice($price);

        self::assertEquals(-1 * $price, $orderDiscount->getUnitPrice());
    }

    public function testUpdateTotal()
    {
        $mockOrderDiscount = [
            'quantity'  => 2,
            'unitPrice' => 0.99
        ];

        $expectedValue = -1 * $mockOrderDiscount['quantity'] * $mockOrderDiscount['unitPrice'];

        $orderDiscount = new OrderDiscountModel;

        $orderDiscount->setQuantity($mockOrderDiscount['quantity'])
            ->setUnitPrice($mockOrderDiscount['unitPrice']);

        $orderDiscount->updateTotal();

        self::assertEquals($expectedValue, $orderDiscount->getTotal());
    }

    public function testToArray()
    {
        $mockOrderDiscount = [
            'name'      => 'applied discount name',
            'code'      => 'CDE',
            'quantity'  => 2,
            'unitPrice' => -0.99,
            'total'     => -1.98
        ];

        $expectedValue = [
            'discount-name' => $mockOrderDiscount['name'],
            'discount-code' => $mockOrderDiscount['code'],
            'quantity'      => $mockOrderDiscount['quantity'],
            'unit-price'    => -1 * $mockOrderDiscount['unitPrice'],
            'total'         => $mockOrderDiscount['total']
        ];

        $orderDiscount = new OrderDiscountModel;

        $orderDiscount->setName($mockOrderDiscount['name'])
            ->setCode($mockOrderDiscount['code'])
            ->setQuantity($mockOrderDiscount['quantity'])
            ->setTotal($mockOrderDiscount['total'])
            ->setUnitPrice($mockOrderDiscount['unitPrice']);

        self::assertEquals($expectedValue, $orderDiscount->toArray());
    }
}
