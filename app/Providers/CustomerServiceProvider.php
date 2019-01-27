<?php

namespace App\Providers;

use App\Repositories\CustomerRepository;
use App\Services\CustomerService;
use App\Services\JSONMapperService;
use Illuminate\Support\ServiceProvider;

/**
 * Class CustomerServiceProvider
 * @package App\Providers
 */
class CustomerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CustomerService::class, function () {
            $mapper = $this->app->make(JSONMapperService::class);

            return new CustomerService(new CustomerRepository(), $mapper);
        });
    }
}
