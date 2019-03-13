<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Tymon\JWTAuth\Providers\LumenServiceProvider::class);
        if ($this->app->environment() == 'local') {
            $this->app->register('Wn\Generators\CommandsServiceProvider');
        }
    }

    function boot()
    {
        Schema::defaultStringLength(191); //Solved by increasing StringLength
    }
}
