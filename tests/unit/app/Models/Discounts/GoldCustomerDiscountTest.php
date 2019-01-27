<?php

namespace tests\unit\app\Models\Discounts;

use App\Models\Discounts\AbstractDiscount;
use App\Models\Discounts\GoldCustomerDiscount;
use App\Models\Order\OrderDiscountModel;
use App\Models\Order\OrderModel;
use Mockery\Mock;

class GoldCustomerDiscountTest extends \TestCase
{
    /** @var GoldCustomerDiscount $discount */
    private $discount;

    /** @var OrderModel|Mock */
    private $mockOrder;

    public function setUp()
    {
        $this->mockOrder = \Mockery::mock(OrderModel::class)->makePartial();
        $this->discount  = new GoldCustomerDiscount($this->mockOrder);
    }

    public function testNameAndCode()
    {
        self::assertInstanceOf(AbstractDiscount::class, $this->discount);
        self::assertEquals('-10% loyalty discount', $this->discount->getName());
        self::assertEquals('GCD', $this->discount->getCode());
    }

    public function testIsOrderEligibleWithEligibleOrder()
    {
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
            ->once()
            ->andReturnSelf();

        $this->mockOrder->shouldReceive('getDiscountedValue')
            ->withNoArgs()
            ->once()
            ->andReturn(24);

        $flag = $this->discount->isOrderEligible();

        self::assertTrue($flag);
    }

    public function testIsOrderEligibleWithIneligibleOrder()
    {
        $this->mockOrder->shouldReceive('getCustomer')
            ->withNoArgs()
            ->once()
            ->andReturnSelf();

        $this->mockOrder->shouldReceive('getRevenue')
            ->withNoArgs()
            ->once()
            ->andReturn(24);

        $this->mockOrder->shouldReceive('getTotal')
            ->withNoArgs()
            ->once()
            ->andReturnSelf();

        $this->mockOrder->shouldReceive('getDiscountedValue')
            ->withNoArgs()
            ->once()
            ->andReturn(24);

        $flag = $this->discount->isOrderEligible();

        self::assertFalse($flag);
    }

    public function testGetApplicableDiscountItem()
    {
        $total = 10;

        $this->mockOrder->shouldReceive('getTotal')
            ->withNoArgs()
            ->once()
            ->andReturnSelf();

        $this->mockOrder->shouldReceive('getDiscountedValue')
            ->withNoArgs()
            ->once()
            ->andReturn($total);

        /** @var OrderDiscountModel[] $discountItems */
        $discountItems = $this->discount->getApplicableDiscountItems();

        foreach ($discountItems as $item) {
            self::assertInstanceOf(OrderDiscountModel::class, $item);
            self::assertEquals(-1 * (.1 * $total), $item->getTotal());
            self::assertEquals(1, $item->getQuantity());
        }
    }
}
