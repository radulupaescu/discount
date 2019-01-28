<?php

namespace App\Api\Controllers;

use App\Models\Order\OrderDiscountModel;
use App\Models\Order\OrderModel;
use App\Services\DiscountService;

/**
 * Class DiscountController
 * @package App\Api\Controllers
 */
class DiscountController extends BaseController
{
    /** @var DiscountService $discountService */
    private $discountService;

    /**
     * DiscountController constructor.
     *
     * @param DiscountService $discountService
     */
    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    /**
     * @param OrderModel $order
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apply(OrderModel $order)
    {
        $discountedOrder = $this->discountService->applyDiscounts($order);

        return response()->json($discountedOrder->toArray(), 200);
    }

    /**
     * @param OrderModel $order
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDiscountItems(OrderModel $order)
    {
        /** @var OrderDiscountModel[] $discountItems */
        $discountItems = $this->discountService->getOrderDiscountItems($order);
        $response = [];

        foreach ($discountItems as $discountItem) {
            $response[] = $discountItem->toArray();
        }

        return response()->json($response, 200);
    }
}
