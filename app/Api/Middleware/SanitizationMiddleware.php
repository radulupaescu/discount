<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class SanitizationMiddleware
 * @package App\Middleware
 */
class SanitizationMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Here we'd implement a sanitization mechanism.
        // We'd take the request payload, analize it and rewrite the request payload.

        return $next($request);
    }
}
