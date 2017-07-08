<?php

namespace Teamo\Common\Providers;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Form::macro('error', function($field)
        {
            if ($errors = \Session::get('errors')) {
                if ($errors->has($field)) {
                    return '<em style="color:#cc0000;font-style:normal;">' . $errors->first($field) . '</em>';
                }
            }

            return '';
        });

        \Form::macro('flash', function()
        {
            if (\Session::has('success')) {
                return '<div class="flash-success">' . \Session::get('success') . '</div>';
            }

            if (\Session::has('failure')) {
                return '<div class="flash-failure">' . \Session::get('failure') . '</div>';
            }

            if (\Session::has('message')) {
                return '<div class="flash-message">' . \Session::get('message') . '</div>';
            }

            return '';
        });

        \Html::macro('cancel', function($defaultUrl)
        {
            $previous = URL::previous();
            $current = URL::current();

            $previous = $previous == $current ? $defaultUrl : $previous;

            return '<a href="'.$previous.'">'.trans('app.cancel').'</a>';
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EntityManagerInterface::class, EntityManager::class);
    }
}
