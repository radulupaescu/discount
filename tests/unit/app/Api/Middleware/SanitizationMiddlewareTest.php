<?php

namespace tests\unit\app\Api\Middleware;

use App\Middleware\SanitizationMiddleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\Mock;

class SanitizationMiddlewareTest extends \TestCase
{
    /** @var SanitizationMiddleware $middleware */
    private $middleware;

    /** @var \Closure $fakeClosure */
    private $fakeClosure;

    /** @var Mock|Request */
    private $request;

    public function setUp()
    {
        $this->middleware = new SanitizationMiddleware;

        $this->fakeClosure = function ($params) {
            return true;
        };

        $this->request = \Mockery::mock(Request::class);
    }

    public function testHandleWithAuthorizedFlagTrue()
    {
        self::assertTrue($this->middleware->handle($this->request, $this->fakeClosure));
    }
}
