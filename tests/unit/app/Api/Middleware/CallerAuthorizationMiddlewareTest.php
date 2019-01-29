<?php

namespace tests\unit\app\Api\Middleware;

use App\Middleware\CallerAuthorizationMiddleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\Mock;

class CallerAuthorizationMiddlewareTest extends \TestCase
{
    /** @var CallerAuthorizationMiddleware $middleware */
    private $middleware;

    /** @var \Closure $fakeClosure */
    private $fakeClosure;

    /** @var Mock|Request */
    private $request;

    public function setUp()
    {
        $this->middleware = new CallerAuthorizationMiddleware;

        $this->fakeClosure = function ($params) {
            return true;
        };

        $this->request = \Mockery::mock(Request::class);
    }

    public function testHandleWithAuthorizedFlagTrue()
    {
        self::assertTrue($this->middleware->handle($this->request, $this->fakeClosure));
    }

    public function testHandleWithAuthorizedFlagFalse()
    {
        $this->middleware->setAuthorized(false);

        /** @var JsonResponse $response */
        $response = $this->middleware->handle($this->request, $this->fakeClosure);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals('{"message":"Unauthorized."}', $response->getContent());
        self::assertEquals(403, $response->getStatusCode());
    }
}
