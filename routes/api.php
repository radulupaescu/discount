<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$api->group([
    'prefix'     => 'api',
    'middleware' => [
        \App\Middleware\CallerAuthorizationMiddleware::class,
        \App\Middleware\SanitizationMiddleware::class,
        \App\Middleware\ParseOrderMiddleware::class
    ]
], function () use ($api) {
    $api->post('discounts/apply', 'DiscountController@apply');
    $api->post('discounts/get', 'DiscountController@getDiscountItems');
});
