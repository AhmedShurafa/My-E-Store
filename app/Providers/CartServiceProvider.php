<?php

namespace App\Providers;

use App\Repositories\Cart\CartRepository;
use App\Repositories\Cart\CookieRepository;
use App\Repositories\Cart\DatabaseRepository;
use App\Repositories\Cart\SesstionRepository;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // singleton // is set return current object
        // bind      // return new object if set or not

        $this->app->singleton(CartRepository::class , function ($app){
            return new DatabaseRepository();
//            return new CookieRepository();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
