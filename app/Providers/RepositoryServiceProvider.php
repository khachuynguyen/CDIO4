<?php

namespace App\Providers;

use App\Interfaces\CartsInterface;
use App\Interfaces\OrderDetailInterface;
use App\Interfaces\OrderInterface;
use App\Repositories\CartRepository;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;
use ProductsInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ProductsInterface::class,ProductRepository::class);
        $this->app->bind(CartsInterface::class,CartRepository::class);
        $this->app->bind(OrderInterface::class,OrderRepository::class);
        $this->app->bind(OrderDetailInterface::class,OrderDetailRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
