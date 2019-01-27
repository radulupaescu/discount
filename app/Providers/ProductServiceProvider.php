<?php

namespace App\Providers;

use App\Repositories\ProductRepository;
use App\Services\JSONMapperService;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

/**
 * Class ProductServiceProvider
 * @package App\Providers
 */
class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProductService::class, function () {
            $mapper = $this->app->make(JSONMapperService::class);

            return new ProductService(new ProductRepository, $mapper);
        });
    }
}
