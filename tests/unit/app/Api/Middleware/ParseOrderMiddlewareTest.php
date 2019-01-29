<?php

namespace tests\unit\app\Api\Middleware;

use App\Exceptions\Customer\CustomerRepositoryException;
use App\Exceptions\Product\ProductRepositoryException;
use App\Middleware\ParseOrderMiddleware;
use App\Models\External\CustomerModel;
use App\Models\External\ProductModel;
use App\Models\Order\OrderDiscountModel;
use App\Models\Order\OrderModel;
use App\Models\Order\OrderProductModel;
use App\Models\Order\OrderTotalModel;
use App\Services\CustomerService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\Mock;

class ParseOrderMiddlewareTest extends \TestCase
{
    /** @var ParseOrderMiddleware $middleware */
    private $middleware;

    /** @var \Closure $fakeClosure */
    private $fakeClosure;

    /** @var Mock|Request */
    private $request;

    /** @var Mock|CustomerService */
    private $customerService;

    /** @var Mock|ProductService */
    private $productService;

    public function setUp()
    {
        $this->customerService = \Mockery::mock(CustomerService::class)->makePartial();
        $this->productService  = \Mockery::mock(ProductService::class)->makePartial();

        $this->middleware = new ParseOrderMiddleware($this->customerService, $this->productService);

        $this->fakeClosure = function ($params) {
            return true;
        };

        $this->request = \Mockery::mock(Request::class);
    }

    public function testHandleWithValidOrderData()
    {
        $orderJson = '{
                      "id"         : "4",
                      "customer-id": "2004",
                      "items"      : [
                        {
                          "product-id": "product-1",
                          "quantity"  : "5",
                          "unit-price": "4.99",
                          "total"     : "24.95"
                        },
                        {
                          "product-id": "product-2",
                          "quantity"  : "11",
                          "unit-price": "5",
                          "total"     : "70"
                        },
                        {
                          "product-id": "product-3",
                          "quantity"  : "1",
                          "unit-price": "12.95",
                          "total"     : "12.95"
                        }
                      ],
                      "total"      : "107.9"
                }';

        $customer = \Mockery::mock(CustomerModel::class)->makePartial();

        $product1 = \Mockery::mock(ProductModel::class)->makePartial();
        $product2 = \Mockery::mock(ProductModel::class)->makePartial();
        $product3 = \Mockery::mock(ProductModel::class)->makePartial();

        $this->request->shouldReceive('json')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->request->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn(json_decode($orderJson, 1));

        $this->customerService->shouldReceive('getCustomerById')
            ->with(2004)
            ->once()
            ->andReturn($customer);

        $this->productService->shouldReceive('getProductById')
            ->once()
            ->with('product-1')
            ->andReturn($product1);

        $this->productService->shouldReceive('getProductById')
            ->once()
            ->with('product-2')
            ->andReturn($product2);

        $this->productService->shouldReceive('getProductById')
            ->once()
            ->with('product-3')
            ->andReturn($product3);

        self::assertTrue($this->middleware->handle($this->request, $this->fakeClosure));
    }

    public function testHandleWithIncompleteButValidOrderData()
    {
        $orderJson = '{
                      "id"         : "4",
                      "customer-id": "2004",
                      "items"      : [
                        {
                          "product-id": "product-1",
                          "quantity"  : "5"
                        },
                        {
                          "product-id": "product-2",
                          "quantity"  : "11",
                          "unit-price": "5",
                          "total"     : "70"
                        },
                        {
                          "product-id": "product-3",
                          "quantity"  : "1",
                          "unit-price": "12.95",
                          "total"     : "12.95"
                        }
                      ],
                      "total"      : "107.9"
                }';

        $customer = \Mockery::mock(CustomerModel::class)->makePartial();

        $product1 = \Mockery::mock(ProductModel::class)->makePartial();
        $product1->shouldReceive('getPrice')
            ->once()
            ->withNoArgs()
            ->andReturn(2.99);

        $product2 = \Mockery::mock(ProductModel::class)->makePartial();
        $product3 = \Mockery::mock(ProductModel::class)->makePartial();

        $this->request->shouldReceive('json')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->request->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn(json_decode($orderJson, 1));

        $this->customerService->shouldReceive('getCustomerById')
            ->with(2004)
            ->once()
            ->andReturn($customer);

        $this->productService->shouldReceive('getProductById')
            ->once()
            ->with('product-1')
            ->andReturn($product1);

        $this->productService->shouldReceive('getProductById')
            ->once()
            ->with('product-2')
            ->andReturn($product2);

        $this->productService->shouldReceive('getProductById')
            ->once()
            ->with('product-3')
            ->andReturn($product3);

        self::assertTrue($this->middleware->handle($this->request, $this->fakeClosure));
    }

    public function testHandleWithInvalidOrderDataBadCustomerId()
    {
        $badCustomerId = "BAD-CUSTOMER-ID";

        $orderJson = '{
                      "id"         : "4",
                      "customer-id": "' . $badCustomerId . '",
                      "items"      : [
                        {
                          "product-id": "product-1",
                          "quantity"  : "5",
                          "unit-price": "4.99",
                          "total"     : "24.95"
                        },
                        {
                          "product-id": "product-2",
                          "quantity"  : "11",
                          "unit-price": "5",
                          "total"     : "70"
                        },
                        {
                          "product-id": "product-3",
                          "quantity"  : "1",
                          "unit-price": "12.95",
                          "total"     : "12.95"
                        }
                      ],
                      "total"      : "107.9"
                }';

        $this->request->shouldReceive('json')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->request->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn(json_decode($orderJson, 1));

        $this->customerService->shouldReceive('getCustomerById')
            ->with($badCustomerId)
            ->once()
            ->andThrow(CustomerRepositoryException::invalidCustomerId($badCustomerId));

        $response = $this->middleware->handle($this->request, $this->fakeClosure);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals('{"code":"INVALID_CUSTOMER_ID","message":"Invalid customer-id found while parsing order: BAD-CUSTOMER-ID"}', $response->getContent());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testHandleWithInvalidOrderDataBadProductId()
    {
        $badProductId = "BAD-PRODUCT-ID";

        $orderJson = '{
                      "id"         : "4",
                      "customer-id": "2004",
                      "items"      : [
                        {
                          "product-id": "' . $badProductId . '",
                          "quantity"  : "5",
                          "unit-price": "4.99",
                          "total"     : "24.95"
                        },
                        {
                          "product-id": "product-2",
                          "quantity"  : "11",
                          "unit-price": "5",
                          "total"     : "70"
                        },
                        {
                          "product-id": "product-3",
                          "quantity"  : "1",
                          "unit-price": "12.95",
                          "total"     : "12.95"
                        }
                      ],
                      "total"      : "107.9"
                }';

        $customer = \Mockery::mock(CustomerModel::class)->makePartial();

        $this->request->shouldReceive('json')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->request->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn(json_decode($orderJson, 1));

        $this->customerService->shouldReceive('getCustomerById')
            ->with(2004)
            ->once()
            ->andReturn($customer);

        $this->productService->shouldReceive('getProductById')
            ->with($badProductId)
            ->once()
            ->andThrow(ProductRepositoryException::invalidProductId($badProductId, null));

        $response = $this->middleware->handle($this->request, $this->fakeClosure);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals('{"code":"INVALID_PRODUCT_ID","message":"Invalid product-id found while parsing order: BAD-PRODUCT-ID"}', $response->getContent());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testHandleWithIncompleteAndInvalidOrderDataMissingProductId()
    {
        $orderJson = '{
                      "id"         : "4",
                      "customer-id": "2004",
                      "items"      : [
                        {
                          "quantity"  : "5"
                        },
                        {
                          "product-id": "product-2",
                          "quantity"  : "11",
                          "unit-price": "5",
                          "total"     : "70"
                        },
                        {
                          "product-id": "product-3",
                          "quantity"  : "1",
                          "unit-price": "12.95",
                          "total"     : "12.95"
                        }
                      ],
                      "total"      : "107.9"
                }';

        $customer = \Mockery::mock(CustomerModel::class)->makePartial();

        $this->request->shouldReceive('json')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->request->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn(json_decode($orderJson, 1));

        $this->customerService->shouldReceive('getCustomerById')
            ->with(2004)
            ->once()
            ->andReturn($customer);

        $response = $this->middleware->handle($this->request, $this->fakeClosure);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals('{"code":"MISSING_ORDER_FIELDS","message":"Incomplete order data. Field product-id is missing."}', $response->getContent());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testHandleWithIncompleteAndInvalidOrderDataMissingProductQuantity()
    {
        $orderJson = '{
                      "id"         : "4",
                      "customer-id": "2004",
                      "items"      : [
                        {
                          "product-id": "product-1"
                        },
                        {
                          "product-id": "product-2",
                          "quantity"  : "11",
                          "unit-price": "5",
                          "total"     : "70"
                        },
                        {
                          "product-id": "product-3",
                          "quantity"  : "1",
                          "unit-price": "12.95",
                          "total"     : "12.95"
                        }
                      ],
                      "total"      : "107.9"
                }';

        $customer = \Mockery::mock(CustomerModel::class)->makePartial();

        $this->request->shouldReceive('json')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->request->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn(json_decode($orderJson, 1));

        $this->customerService->shouldReceive('getCustomerById')
            ->with(2004)
            ->once()
            ->andReturn($customer);

        $response = $this->middleware->handle($this->request, $this->fakeClosure);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals('{"code":"MISSING_ORDER_FIELDS","message":"Incomplete order data. Field quantity is missing."}', $response->getContent());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testHandleWithIncompleteAndInvalidOrderDataMissingOrderId()
    {
        $orderJson = '{
                      "customer-id": "2004",
                      "items"      : [],
                      "total"      : "107.9"
                }';

        $this->request->shouldReceive('json')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->request->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn(json_decode($orderJson, 1));

        $response = $this->middleware->handle($this->request, $this->fakeClosure);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals('{"code":"MISSING_ORDER_FIELDS","message":"Incomplete order data. Field id is missing."}', $response->getContent());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testHandleWithIncompleteAndInvalidOrderDataMissingCustomerId()
    {
        $orderJson = '{
                      "id"         : "4",
                      "items"      : [],
                      "total"      : "107.9"
                }';

        $this->request->shouldReceive('json')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->request->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn(json_decode($orderJson, 1));

        $response = $this->middleware->handle($this->request, $this->fakeClosure);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals('{"code":"MISSING_ORDER_FIELDS","message":"Incomplete order data. Field customer-id is missing."}', $response->getContent());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testHandleWithIncompleteAndInvalidOrderDataMissingOrderItems()
    {
        $orderJson = '{
                      "id"         : "4",
                      "customer-id": "2004",
                      "total"      : "107.9"
                }';

        $this->request->shouldReceive('json')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->request->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn(json_decode($orderJson, 1));

        $response = $this->middleware->handle($this->request, $this->fakeClosure);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals('{"code":"MISSING_ORDER_FIELDS","message":"Incomplete order data. Field items is missing."}', $response->getContent());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testHandleWithIncompleteAndInvalidOrderDataMissingOrderTotal()
    {
        $orderJson = '{
                      "id"         : "4",
                      "customer-id": "2004",
                      "items"      : []
                }';

        $this->request->shouldReceive('json')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->request->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn(json_decode($orderJson, 1));

        $response = $this->middleware->handle($this->request, $this->fakeClosure);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals('{"code":"MISSING_ORDER_FIELDS","message":"Incomplete order data. Field total is missing."}', $response->getContent());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testHandleWithInvalidOrderDataUnknownReason()
    {
        $orderJson = '{
                      "id"         : "4",
                      "customer-id": "2004",
                      "items"      : [
                        {
                          "product-id": "product-1",
                          "quantity"  : "5",
                          "unit-price": "4.99",
                          "total"     : "24.95"
                        },
                        {
                          "product-id": "product-2",
                          "quantity"  : "11",
                          "unit-price": "5",
                          "total"     : "70"
                        },
                        {
                          "product-id": "product-3",
                          "quantity"  : "1",
                          "unit-price": "12.95",
                          "total"     : "12.95"
                        }
                      ],
                      "total"      : "107.9"
                }';

        $this->request->shouldReceive('json')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->request->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn(json_decode($orderJson, 1));

        $this->customerService->shouldReceive('getCustomerById')
            ->with(2004)
            ->once()
            ->andThrow(new \Exception('generic message'));

        $response = $this->middleware->handle($this->request, $this->fakeClosure);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals('{"code":"UNKNOWN_EXCEPTION_CODE","message":"Service has raised an error"}', $response->getContent());
        self::assertEquals(200, $response->getStatusCode());
    }
}
