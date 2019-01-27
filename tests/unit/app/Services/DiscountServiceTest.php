<?php

namespace tests\unit\app\Services;

use App\Contracts\Discount;
use App\Models\External\ProductModel;
use App\Models\Order\OrderDiscountModel;
use App\Models\Order\OrderModel;
use App\Models\Order\OrderProductModel;
use App\Services\DiscountService;
use Mockery\Mock;

class DiscountServiceTest extends \TestCase
{
    /** @var OrderModel|Mock $mockOrder */
    private $mockOrder;

    /** @var DiscountService $service */
    private $service;

    public function setUp()
    {
        $this->mockOrder = \Mockery::mock(OrderModel::class)->makePartial();
        $this->service = app()->make(DiscountService::class);
    }

    public function testApplyDiscounts()
    {
        for ($i = 1; $i < 4; $i++) {
            $item = new OrderProductModel;

            /** @var Mock|ProductModel $mockProduct */
            $mockProduct = \Mockery::mock(ProductModel::class)->makePartial();
            $mockProduct->shouldReceive('getCategory')
                ->twice()
                ->withNoArgs()
                ->andReturn(1);

            $item->setProduct($mockProduct)
                ->setQuantity(1)
                ->setUnitPrice($i * 10);

            $this->mockOrder->addItem($item);
        }

        $this->mockOrder->shouldReceive('getCustomer')
            ->withNoArgs()
            ->once()
            ->andReturnSelf();

        $this->mockOrder->shouldReceive('getRevenue')
            ->withNoArgs()
            ->once()
            ->andReturn(2004);

        $this->mockOrder->shouldReceive('getTotal')
            ->withNoArgs()
            ->twice()
            ->andReturnSelf();

        $this->mockOrder->shouldReceive('getDiscountedValue')
            ->withNoArgs()
            ->twice()
            ->andReturn(24);

        $this->mockOrder->shouldReceive('setDiscountItems')
            ->withAnyArgs()
            ->once()
            ->andReturnSelf();

        $this->mockOrder->shouldReceive('applyDiscounts')
            ->withNoArgs()
            ->once()
            ->andReturnSelf();

        $order = $this->service->applyDiscounts($this->mockOrder);

        self::assertInstanceOf(OrderModel::class, $order);
        self::assertSame($this->mockOrder, $order);
    }

    public function testGetOrderDiscountItems()
    {
        for ($i = 1; $i < 4; $i++) {
            $item = new OrderProductModel;

            /** @var Mock|ProductModel $mockProduct */
            $mockProduct = \Mockery::mock(ProductModel::class)->makePartial();
            $mockProduct->shouldReceive('getCategory')
                ->twice()
                ->withNoArgs()
                ->andReturn(1);

            $item->setProduct($mockProduct)
                ->setQuantity(1)
                ->setUnitPrice($i * 10);

            $this->mockOrder->addItem($item);
        }

        $this->mockOrder->shouldReceive('getCustomer')
            ->withNoArgs()
            ->once()
            ->andReturnSelf();

        $this->mockOrder->shouldReceive('getRevenue')
            ->withNoArgs()
            ->once()
            ->andReturn(2004);

        $this->mockOrder->shouldReceive('getTotal')
            ->withNoArgs()
            ->twice()
            ->andReturnSelf();

        $this->mockOrder->shouldReceive('getDiscountedValue')
            ->withNoArgs()
            ->twice()
            ->andReturn(24);

        $discountItems = $this->service->getOrderDiscountItems($this->mockOrder);

        foreach ($discountItems as $discountItem) {
            self::assertInstanceOf(OrderDiscountModel::class, $discountItem);
        }
    }
}
