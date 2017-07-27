<?php

namespace Teamo\Common\Provider;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Teamo\Common\Infrastructure\Messaging\MessageProducer;
use Teamo\Common\Infrastructure\Messaging\RabbitMQ\RabbitMQMessageProducer;

class AMQPServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MessageProducer::class, function () {
            return new RabbitMQMessageProducer(new AMQPStreamConnection(
                Config::get('queue.connections.rabbitmq.host'),
                Config::get('queue.connections.rabbitmq.port'),
                Config::get('queue.connections.rabbitmq.user'),
                Config::get('queue.connections.rabbitmq.password')
            ));
        });
    }
}
