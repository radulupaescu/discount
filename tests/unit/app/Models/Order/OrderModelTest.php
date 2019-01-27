<?php

namespace tests\unit\app\Models\Order;

use App\Models\External\CustomerModel;
use App\Models\External\ProductModel;
use App\Models\Order\OrderDiscountModel;
use App\Models\Order\OrderModel;
use App\Models\Order\OrderProductModel;
use App\Models\Order\OrderTotalModel;
use Mockery\Mock;

class OrderModelTest extends \TestCase
{
    /** @var int $orderId */
    private $orderId;

    /** @var CustomerModel|Mock $customer */
    private $customer;

    /** @var ProductModel|Mock $product */
    private $product;

    /** @var OrderTotalModel|Mock $total */
    private $total;

    /** @var OrderProductModel $orderProduct */
    private $orderProduct;

    /** @var OrderDiscountModel $orderDiscount */
    private $orderDiscount;

    public function setUp()
    {
        $this->orderId  = 2004;
        $this->customer = \Mockery::mock(CustomerModel::class)->makePartial();
        $this->product  = \Mockery::mock(ProductModel::class)->makePartial();
        $this->total    = \Mockery::mock(OrderTotalModel::class)->makePartial();

        $orderProductData = [
            'quantity'  => 2,
            'unitPrice' => 2.99,
            'total'     => 5.98
        ];

        $this->orderProduct = new OrderProductModel;

        $this->orderProduct->setProduct($this->product)
            ->setQuantity($orderProductData['quantity'])
            ->setTotal($orderProductData['total'])
            ->setUnitPrice($orderProductData['unitPrice']);

        $orderDiscountData = [
            'name'      => 'applied discount name',
            'code'      => 'CDE',
            'quantity'  => 2,
            'unitPrice' => 0.99,
            'total'     => -1.98
        ];

        $this->orderDiscount = new OrderDiscountModel;

        $this->orderDiscount->setName($orderDiscountData['name'])
            ->setCode($orderDiscountData['code'])
            ->setQuantity($orderDiscountData['quantity'])
            ->setTotal($orderDiscountData['total'])
            ->setUnitPrice($orderDiscountData['unitPrice']);
    }

    public function testGettersAndSettersSetDataCorrectly()
    {
        $order = new OrderModel;

        $order->setId($this->orderId)
            ->setCustomer($this->customer)
            ->setTotal($this->total)
            ->setItems([])
            ->setDiscountItems([$this->orderDiscount]);

        $order->addItem($this->orderProduct);

        $hash          = spl_object_hash($this->orderProduct);
        $expectedItems = [
            $hash => $this->orderProduct
        ];

        self::assertEquals($this->orderId, $order->getId());
        self::assertSame($this->customer, $order->getCustomer());
        self::assertSame($this->total, $order->getTotal());
        self::assertEquals([$this->orderDiscount], $order->getDiscountItems());
        self::assertEquals($expectedItems, $order->getItems());
        self::assertSame($this->orderProduct, $order->getItem($hash));
    }

    public function testApplyDiscounts()
    {
        $order = $this->getMockOrder();

        $this->total->shouldReceive('setDiscount')
            ->once()
            ->with($this->orderDiscount->getTotal())
            ->andReturnSelf();

        $this->total->shouldReceive('makeDiscountedValue')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $order->applyDiscounts();
    }

    public function testToArray()
    {
        $order = $this->getMockOrder();

        $this->customer->shouldReceive('getId')
            ->once()
            ->withNoArgs()
            ->andReturn(2404);

        $this->total->shouldReceive('getOriginalValue')
            ->once()
            ->withNoArgs()
            ->andReturn(24.04);

        $this->total->shouldReceive('getDiscount')
            ->once()
            ->withNoArgs()
            ->andReturn(-24);


        $this->total->shouldReceive('getDiscountedValue')
            ->once()
            ->withNoArgs()
            ->andReturn(20.04);

        $items = [];

        foreach ($order->getItems() as $item) {
            $items[] = $item->toArray();
        }

        $discountedItems = [];

        foreach ($order->getDiscountItems() as $item) {
            $discountedItems[] = $item->toArray();
        }

        $expectedValue = [
            'id'               => $this->orderId,
            'customer-id'      => 2404,
            'items'            => $items,
            'discounted-items' => $discountedItems,
            'total'            => 24.04,
            'discount'         => -24,
            'discounted-total' => 20.04
        ];

        self::assertEquals($expectedValue, $order->toArray());
    }

    private function getMockOrder()
    {
        $order = new OrderModel;

        $order->setId($this->orderId)
            ->setCustomer($this->customer)
            ->setTotal($this->total)
            ->setItems([])
            ->setDiscountItems([$this->orderDiscount]);

        $order->addItem($this->orderProduct);

        return $order;
    }
}
