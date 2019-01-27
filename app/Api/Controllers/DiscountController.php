<?php

namespace App\Api\Controllers;

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
    public function discount(OrderModel $order)
    {
        $discountedOrder = $this->discountService->applyDiscounts($order);

        return response()->json($discountedOrder->toArray(), 200);
    }
}
