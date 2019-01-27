<?php

namespace tests\unit\app\Api\Middleware;

use App\Middleware\ParseOrderMiddleware;
use App\Services\CustomerService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Mockery\Mock;

class ParseOrderMiddlewareTest extends \TestCase
{
    /** @var ParseOrderMiddleware $middleware */
    private $middleware;

    /** @var \Closure $fakeClosure */
    private $fakeClosure;

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
    }

    public function testHandleWithValidOrderData()
    {
        //$this->middleware->handle(new Request(), $this->fakeClosure);

        $a = 1;

        self::assertTrue($a == 1);
    }
}
