<?php

namespace tests\unit\app\Models\Discounts;

use App\Models\Discounts\AbstractDiscount;
use App\Models\Discounts\SixthForFree;
use App\Models\External\ProductModel;
use App\Models\Order\OrderDiscountModel;
use App\Models\Order\OrderModel;
use App\Models\Order\OrderProductModel;
use Mockery\Mock;

class SixthForFreeTest extends \TestCase
{
    /** @var SixthForFree $discount */
    private $discount;

    /** @var OrderModel|Mock */
    private $mockOrder;

    public function setUp()
    {
        $this->mockOrder = \Mockery::mock(OrderModel::class)->makePartial();
        $this->discount  = new SixthForFree($this->mockOrder);
    }

    public function testNameAndCode()
    {
        self::assertInstanceOf(AbstractDiscount::class, $this->discount);
        self::assertEquals('Sixth switch for free', $this->discount->getName());
        self::assertEquals('SFF', $this->discount->getCode());
    }

    public function testIsOrderEligibleWithEligibleOrder()
    {
        $items             = [];
        $quantityScenarios = [3, 5, 6];

        for ($i = 0; $i < 3; $i++) {
            $item = new OrderProductModel;

            /** @var Mock|ProductModel $mockProduct */
            $mockProduct = \Mockery::mock(ProductModel::class)->makePartial();
            $mockProduct->shouldReceive('getCategory')
                ->once()
                ->withNoArgs()
                ->andReturn(2);

            $item->setProduct($mockProduct)
                ->setQuantity($quantityScenarios[$i])
                ->setUnitPrice(10 + $i);

            $hash         = spl_object_hash($item);
            $items[$hash] = $item;
        }

        $this->mockOrder->setItems($items);

        $flag = $this->discount->isOrderEligible();

        self::assertTrue($flag);
    }

    public function testIsOrderEligibleWithIneligibleOrder()
    {
        $items             = [];
        $quantityScenarios = [3, 1];

        for ($i = 0; $i < 2; $i++) {
            $item = new OrderProductModel;

            /** @var Mock|ProductModel $mockProduct */
            $mockProduct = \Mockery::mock(ProductModel::class)->makePartial();
            $mockProduct->shouldReceive('getCategory')
                ->once()
                ->withNoArgs()
                ->andReturn(2);

            $item->setProduct($mockProduct)
                ->setQuantity($quantityScenarios[$i])
                ->setUnitPrice(10 + $i);

            $hash         = spl_object_hash($item);
            $items[$hash] = $item;
        }

        $this->mockOrder->setItems($items);

        $flag = $this->discount->isOrderEligible();

        self::assertFalse($flag);
    }

    public function testGetApplicableDiscountItems()
    {
        $items             = [];
        $quantityScenarios = [3, 5, 6, 12, 11];

        for ($i = 0; $i < 5; $i++) {
            $item = new OrderProductModel;

            /** @var Mock|ProductModel $mockProduct */
            $mockProduct = \Mockery::mock(ProductModel::class)->makePartial();
            $mockProduct->shouldReceive('getCategory')
                ->once()
                ->withNoArgs()
                ->andReturn(2);

            $item->setProduct($mockProduct)
                ->setQuantity($quantityScenarios[$i])
                ->setUnitPrice(10 + $i);

            $hash         = spl_object_hash($item);
            $items[$hash] = $item;
        }

        $this->mockOrder->setItems($items);

        $discountItems = $this->discount->getApplicableDiscountItems();

        self::assertEquals(6, count($discountItems));

        $extraProduct = $discountItems[0];
        self::assertInstanceOf(OrderProductModel::class, $extraProduct);
        self::assertEquals(1, $extraProduct->getQuantity());
        self::assertEquals(11.0, $extraProduct->getUnitPrice());

        $discountForExtraProduct = $discountItems[1];
        self::assertInstanceOf(OrderDiscountModel::class, $discountForExtraProduct);
        self::assertEquals(1, $discountForExtraProduct->getQuantity());
        self::assertEquals(-11.0, $discountForExtraProduct->getUnitPrice());

        $discountForThird = $discountItems[2];
        self::assertInstanceOf(OrderDiscountModel::class, $discountForThird);
        self::assertEquals(1, $discountForThird->getQuantity());
        self::assertEquals(-12.0, $discountForThird->getUnitPrice());

        $multipleDiscount = $discountItems[3];
        self::assertInstanceOf(OrderDiscountModel::class, $multipleDiscount);
        self::assertEquals(2, $multipleDiscount->getQuantity());
        self::assertEquals(-13.0, $multipleDiscount->getUnitPrice());

        $secondExtraProduct = $discountItems[4];
        self::assertInstanceOf(OrderProductModel::class, $secondExtraProduct);
        self::assertEquals(1, $secondExtraProduct->getQuantity());
        self::assertEquals(14.0, $secondExtraProduct->getUnitPrice());

        $secondMultipleDiscount = $discountItems[5];
        self::assertInstanceOf(OrderDiscountModel::class, $secondMultipleDiscount);
        self::assertEquals(2, $secondMultipleDiscount->getQuantity());
        self::assertEquals(-14.0, $secondMultipleDiscount->getUnitPrice());

    }

}
