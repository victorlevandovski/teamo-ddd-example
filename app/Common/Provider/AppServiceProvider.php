<?php

namespace Teamo\Common\Provider;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Teamo\Common\Http\ViewComposer\AppComposer;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentManager;
use Teamo\Project\Infrastructure\FileSystem\LocalAttachmentManager;

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
        $this->app->singleton(AttachmentManager::class, LocalAttachmentManager::class);
    }
}
