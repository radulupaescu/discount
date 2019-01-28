<?php

namespace tests\unit\app\Api\Controllers;

use App\Api\Controllers\DiscountController;
use App\Models\External\CustomerModel;
use App\Models\Order\OrderModel;
use App\Models\Order\OrderTotalModel;
use App\Services\DiscountService;
use Mockery\Mock;

class DiscountControllerTest extends \TestCase
{
    /** @var Mock|DiscountService $discountService */
    private $discountService;

    /** @var DiscountController $controller */
    private $controller;

    public function setUp()
    {
        $this->discountService = \Mockery::mock(DiscountService::class)->makePartial();

        $this->controller = new DiscountController($this->discountService);
    }

    public function testCallingDiscountWithValidOrderReturnsDiscounts()
    {
        $order = new OrderModel();
        $order->setId(1);

        /** @var CustomerModel|Mock $customer */
        $customer = \Mockery::mock(CustomerModel::class)->makePartial();
        $customer->shouldReceive('getId')
            ->withNoArgs()
            ->twice()
            ->andReturn(999);
        $order->setCustomer($customer);

        $order->setItems([]);
        $order->setDiscountItems([]);

        /** @var OrderTotalModel|Mock $total */
        $total = \Mockery::mock(OrderTotalModel::class)->makePartial();
        $total->shouldReceive('getOriginalValue')
            ->twice()
            ->withNoArgs()
            ->andReturn(100);

        $total->shouldReceive('getDiscount')
            ->twice()
            ->withNoArgs()
            ->andReturn(20);

        $total->shouldReceive('getDiscountedValue')
            ->twice()
            ->withNoArgs()
            ->andReturn(80);

        $order->setTotal($total);

        $expectedResponse = json_encode($order->toArray());

        $this->discountService->shouldReceive('applyDiscounts')
            ->once()
            ->with($order)
            ->andReturn($order);

        $response = $this->controller->apply($order);

        self::assertEquals($expectedResponse, $response->content());
    }

}
