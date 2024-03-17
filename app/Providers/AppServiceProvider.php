<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind("pathao", function () {
            return new \App\Pathao\Manage\Manage(
                new \App\Pathao\Apis\AreaApi(),
                new \App\Pathao\Apis\StoreApi(),
                new \App\Pathao\Apis\OrderApi()
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
