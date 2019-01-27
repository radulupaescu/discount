<?php

namespace tests\unit\app\Models\Discounts;

use App\Models\Discounts\AbstractDiscount;
use App\Models\Discounts\TwentyPercentOnCheapestThirdTool;
use App\Models\External\ProductModel;
use App\Models\Order\OrderDiscountModel;
use App\Models\Order\OrderModel;
use App\Models\Order\OrderProductModel;
use Mockery\Mock;

class TwentyPercentOnCheapestThirdToolTest extends \TestCase
{
    /** @var TwentyPercentOnCheapestThirdTool $discount */
    private $discount;

    /** @var OrderModel|Mock */
    private $mockOrder;

    public function setUp()
    {
        $this->mockOrder = \Mockery::mock(OrderModel::class)->makePartial();
        $this->discount  = new TwentyPercentOnCheapestThirdTool($this->mockOrder);
    }

    public function testNameAndCode()
    {
        self::assertInstanceOf(AbstractDiscount::class, $this->discount);
        self::assertEquals('-20% for third tool', $this->discount->getName());
        self::assertEquals('TP3T', $this->discount->getCode());
    }

    public function testIsOrderEligibleWithEligibleOrder()
    {
        $items = [];

        for ($i = 1; $i < 4; $i++) {
            $item = new OrderProductModel;

            /** @var Mock|ProductModel $mockProduct */
            $mockProduct = \Mockery::mock(ProductModel::class)->makePartial();
            $mockProduct->shouldReceive('getCategory')
                ->once()
                ->withNoArgs()
                ->andReturn(1);

            $item->setProduct($mockProduct)
                ->setQuantity(1)
                ->setUnitPrice($i * 10);

            $hash         = spl_object_hash($item);
            $items[$hash] = $item;
        }

        $this->mockOrder->setItems($items);

        $flag = $this->discount->isOrderEligible();

        self::assertTrue($flag);
    }

    public function testIsOrderEligibleWithIneligibleOrder()
    {
        $items = [];

        for ($i = 1; $i < 2; $i++) {
            $item = new OrderProductModel;

            /** @var Mock|ProductModel $mockProduct */
            $mockProduct = \Mockery::mock(ProductModel::class)->makePartial();
            $mockProduct->shouldReceive('getCategory')
                ->once()
                ->withNoArgs()
                ->andReturn(1);

            $item->setProduct($mockProduct)
                ->setQuantity(1)
                ->setUnitPrice($i * 10);

            $hash         = spl_object_hash($item);
            $items[$hash] = $item;
        }

        $this->mockOrder->setItems($items);

        $flag = $this->discount->isOrderEligible();

        self::assertFalse($flag);
    }


    public function testGetApplicableDiscountItem()
    {
        $items = [];

        for ($i = 1; $i < 4; $i++) {
            $item = new OrderProductModel;

            /** @var Mock|ProductModel $mockProduct */
            $mockProduct = \Mockery::mock(ProductModel::class)->makePartial();
            $mockProduct->shouldReceive('getCategory')
                ->once()
                ->withNoArgs()
                ->andReturn(1);

            $item->setProduct($mockProduct)
                ->setQuantity(1)
                ->setUnitPrice($i * 10);

            $hash         = spl_object_hash($item);
            $items[$hash] = $item;
        }

        $this->mockOrder->setItems($items);

        /** @var OrderProductModel $cheapest */
        $cheapest = $items[array_keys($items)[0]];

        $this->discount->isOrderEligible();

        /** @var OrderDiscountModel[] $discountItems */
        $discountItems = $this->discount->getApplicableDiscountItems();

        foreach ($discountItems as $item) {
            self::assertInstanceOf(OrderDiscountModel::class, $item);
            self::assertEquals(-1 * (.2 * $cheapest->getUnitPrice()), $item->getTotal());
            self::assertEquals(1, $item->getQuantity());
        }
    }
}
