<?php

namespace App\Providers;

use App\Events\OrderPlaced;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Observers\ProductObserver;
use App\Policies\AddressPolicy;
use App\Policies\OrderPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Product::observe(ProductObserver::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Address::class, AddressPolicy::class);

         
    }
}
