<?php

namespace App\Providers;

use App\Services\JSONMapperService;
use Illuminate\Support\ServiceProvider;

/**
 * Class JSONMapperServiceProvider
 * @package App\Providers
 */
class JSONMapperServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(JSONMapperService::class);
    }
}
