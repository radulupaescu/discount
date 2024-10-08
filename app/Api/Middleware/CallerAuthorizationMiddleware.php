<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class CallerAuthorizationMiddleware
 * @package App\Middleware
 */
class CallerAuthorizationMiddleware
{
    /** @var bool $authorized */
    private $authorized = true;

    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Here we'd implement an authorization mechanism. If access is granted, the request follows it's normal flow.
        // If not, a response is sent to the caller with an appropriate message.

        // Change this flag to see how this would work.
        $authorized = $this->authorized;

        if ($authorized) {
            return $next($request);
        } else {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }
    }

    /**
     * @param bool $value
     */
    public function setAuthorized($value)
    {
        $this->authorized = $value;
    }
}
