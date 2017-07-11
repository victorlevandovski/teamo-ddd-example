<?php

namespace Teamo\Common\Provider;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Teamo\Common\Http\ViewComposer\AppComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', AppComposer::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
