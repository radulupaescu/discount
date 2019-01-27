<?php

namespace App\Providers;

use App\Services\DiscountService;
use Illuminate\Support\ServiceProvider;

/**
 * Class DiscountServiceProvider
 * @package App\Providers
 */
class DiscountServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DiscountService::class, function () {
            return new DiscountService;
        });
    }
}
