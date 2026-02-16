<?php

namespace App\Providers;

use App\Interfaces\Categories\CategoryInterface;
use App\Interfaces\Orders\OrderMessageInterface;
use App\Interfaces\Products\ProductInterface;
use App\Repositories\Categories\CategoryRepository;
use App\Repositories\Orders\OrderMessageRepository;
use App\Repositories\Products\ProductRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductInterface::class, ProductRepository::class);
        $this->app->bind(OrderMessageInterface::class, OrderMessageRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
