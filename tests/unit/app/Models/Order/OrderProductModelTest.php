<?php

namespace tests\unit\app\Models\Order;

use App\Models\External\ProductModel;
use App\Models\Order\OrderProductModel;
use Mockery\Mock;

class OrderProductModelTest extends \TestCase
{
    /** @var ProductModel|Mock $productMock */
    private $productMock;

    public function setUp()
    {
        $this->productMock = \Mockery::mock(ProductModel::class)->makePartial();
    }

    public function testGettersAndSettersSetDataCorrectly()
    {
        $mockOrderProduct = [
            'quantity'  => 2,
            'unitPrice' => 0.99,
            'total'     => 1.98
        ];

        $orderProduct = new OrderProductModel;

        $orderProduct->setProduct($this->productMock)
            ->setQuantity($mockOrderProduct['quantity'])
            ->setTotal($mockOrderProduct['total'])
            ->setUnitPrice($mockOrderProduct['unitPrice']);

        self::assertSame($this->productMock, $orderProduct->getProduct());
        self::assertEquals($mockOrderProduct['quantity'], $orderProduct->getQuantity());
        self::assertEquals($mockOrderProduct['unitPrice'], $orderProduct->getUnitPrice());
        self::assertEquals($mockOrderProduct['total'], $orderProduct->getTotal());
    }

    public function testUpdateTotal()
    {
        $mockOrderProduct = [
            'quantity'  => 2,
            'unitPrice' => 0.99
        ];

        $expectedValue = $mockOrderProduct['quantity'] * $mockOrderProduct['unitPrice'];

        $orderProduct = new OrderProductModel;

        $orderProduct->setQuantity($mockOrderProduct['quantity'])
            ->setUnitPrice($mockOrderProduct['unitPrice']);

        $orderProduct->updateTotal();

        self::assertEquals($expectedValue, $orderProduct->getTotal());
    }

    public function testToArray()
    {
        $mockProductId = 'some-product-id';

        $mockOrderProduct = [
            'quantity'  => 2,
            'unitPrice' => 0.99,
            'total'     => 1.98
        ];

        $expectedValue = [
            'product-id' => $mockProductId,
            'quantity'   => $mockOrderProduct['quantity'],
            'unit-price' => $mockOrderProduct['unitPrice'],
            'total'      => $mockOrderProduct['total']
        ];

        $orderProduct = new OrderProductModel;

        $orderProduct->setProduct($this->productMock)
            ->setQuantity($mockOrderProduct['quantity'])
            ->setTotal($mockOrderProduct['total'])
            ->setUnitPrice($mockOrderProduct['unitPrice']);

        $this->productMock->shouldReceive('getId')
            ->once()
            ->withNoArgs()
            ->andReturn($mockProductId);

        self::assertEquals($expectedValue, $orderProduct->toArray());
    }
}
