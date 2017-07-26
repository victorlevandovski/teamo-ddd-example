<?php

namespace Teamo\Common\Provider;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Teamo\Common\Http\ViewComposer\AppComposer;
use Teamo\Common\Infrastructure\Serializer;
use Teamo\Common\Port\Adapter\Messaging\MessageProducer;
use Teamo\Common\Port\Adapter\Messaging\RabbitMQ\RabbitMQMessageProducer;
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
        $this->app->singleton(MessageProducer::class, RabbitMQMessageProducer::class);
        $this->app->singleton(AttachmentManager::class, LocalAttachmentManager::class);
        $this->app->singleton('serializer', Serializer::class);
    }
}
