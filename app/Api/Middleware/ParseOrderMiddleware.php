<?php

namespace App\Middleware;

use App\Exception\ExceptionCodes;
use App\Exceptions\Customer\CustomerRepositoryException;
use App\Exceptions\Order\OrderParserException;
use App\Exceptions\Product\ProductRepositoryException;
use App\Models\Order\OrderProductModel;
use App\Models\Order\OrderModel;
use App\Models\Order\OrderTotalModel;
use Closure;
use App\Services\CustomerService;
use App\Services\ProductService;
use Illuminate\Http\Request;

/**
 * Class ParseOrderMiddleware
 * @package App\Middleware
 */
class ParseOrderMiddleware
{
    /** @var CustomerService $customerService */
    private $customerService;

    /** @var ProductService $productService */
    private $productService;

    /**
     * ParseOrderMiddleware constructor.
     *
     * @param CustomerService $customerService
     * @param ProductService  $productService
     */
    public function __construct(CustomerService $customerService, ProductService $productService)
    {
        $this->customerService = $customerService;
        $this->productService  = $productService;
    }

    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $orderData = $request->json()->all();

        try {
            $this->validateRawData($orderData);
            $order = $this->mapData($orderData);
        } catch (OrderParserException $ope) {
            return response()->json([
                'code'    => $ope->getCustomCode(),
                'message' => $ope->getMessage()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code'    => ExceptionCodes::UNKNOWN_EXCEPTION_CODE,
                'message' => 'Service has raised an error'
            ], 200);
        }

        app()->instance(OrderModel::class, $order);

        return $next($request);
    }

    /**
     * @param array $orderData
     *
     * @return OrderModel
     * @throws OrderParserException
     */
    private function mapData($orderData)
    {

        $orderTotal = new OrderTotalModel;
        $orderTotal->setOriginalValue($orderData['total'])
            ->setDiscount(0)
            ->setDiscountedValue($orderData['total']);

        $order = new OrderModel;
        $order->setId($orderData['id'])
            ->setCustomer($this->fetchCustomer($orderData['customer-id']))
            ->setTotal($orderTotal);

        foreach ($orderData['items'] as $orderItemData) {
            $orderItem = $this->parseItem($orderItemData);
            $order->addItem($orderItem);
        }

        return $order;
    }

    /**
     * @param array $orderItemData
     *
     * @return OrderProductModel
     * @throws OrderParserException
     */
    private function parseItem($orderItemData)
    {
        $this->validateItemData($orderItemData);
        $product = $this->fetchItemProduct($orderItemData);

        if (!isset($orderItemData['unit-price'])) {
            $orderItemData['unit-price'] = $product->getPrice();
        }

        if (!isset($orderItemData['total'])) {
            $orderItemData['total'] = $orderItemData['quantity'] * $orderItemData['unit-price'];
        }

        $orderItem = new OrderProductModel;
        $orderItem->setProduct($product)
            ->setQuantity($orderItemData['quantity'])
            ->setUnitPrice($orderItemData['unit-price'])
            ->setTotal($orderItemData['total']);

        return $orderItem;
    }

    /**
     * @param array $orderItemData
     *
     * @return \App\Models\External\ProductModel
     * @throws OrderParserException
     */
    private function fetchItemProduct($orderItemData)
    {
        try {
            $product = $this->productService->getProductById($orderItemData['product-id']);
        } catch (ProductRepositoryException $pre) {
            throw OrderParserException::orderProductNotFound($orderItemData['product-id'], $pre);
        }

        return $product;
    }

    /**
     * @param int $customerId
     *
     * @return \App\Models\External\CustomerModel
     * @throws OrderParserException
     */
    private function fetchCustomer($customerId)
    {
        try {
            $product = $this->customerService->getCustomerById($customerId);
        } catch (CustomerRepositoryException $cre) {
            throw OrderParserException::orderCustomerNotFound($customerId, $cre);
        }

        return $product;
    }

    /**
     * @param array $orderItemData
     *
     * @return bool
     * @throws OrderParserException
     */
    private function validateItemData($orderItemData)
    {
        if (!isset($orderItemData['product-id'])) {
            throw OrderParserException::missingInformation('product-id');
        }

        if (!isset($orderItemData['quantity'])) {
            throw OrderParserException::missingInformation('quantity');
        }

        return true;
    }

    /**
     * @param array $orderData
     *
     * @return bool
     * @throws OrderParserException
     */
    private function validateRawData($orderData)
    {
        if (!isset($orderData['id'])) {
            throw OrderParserException::missingInformation('id');
        }

        if (!isset($orderData['customer-id'])) {
            throw OrderParserException::missingInformation('customer-id');
        }

        if (!isset($orderData['items'])) {
            throw OrderParserException::missingInformation('items');
        }

        if (!isset($orderData['total'])) {
            throw OrderParserException::missingInformation('total');
        }

        return true;
    }
}
