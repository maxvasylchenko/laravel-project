<?php

namespace App\Providers;

use App\Notifications\OrderCreatedNotification;
use App\Repositories\Contracts\ImageRepositoryContract;
use App\Repositories\Contracts\OrderRepositoryContract;
use App\Repositories\Contracts\ProductRepositoryContract;
use App\Repositories\ImageRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\Contracts\InvoicesServiceContract;
use App\Services\Contracts\PaypalServiceContract;
use App\Services\InvoicesService;
use App\Services\PaypalService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        ProductRepositoryContract::class => ProductRepository::class,
        ImageRepositoryContract::class => ImageRepository::class,
        OrderRepositoryContract::class => OrderRepository::class,
        PaypalServiceContract::class => PaypalService::class,
        InvoicesServiceContract::class => InvoicesService::class
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();
//        app()->when(ProductRepository::class)
//        ->needs(ImageRepositoryContract::class)
//        ->give(ImageRepository::class);
    }
}
