<?php

namespace Teamo\Common\Provider;

use Illuminate\Support\ServiceProvider;
use Teamo\Common\Domain\DomainEventPublisher;
use Teamo\Project\Application\Subscriber\UserRegisteredSubscriber;
use Teamo\Project\Application\Subscriber\UserRenamedSubscriber;

class DomainEventPublisherServiceProvider extends ServiceProvider
{
    /**
     * Global subscribers
     *
     * @return void
     */
    public function boot()
    {
        /** @var DomainEventPublisher $domainEventPublisher */
        $domainEventPublisher = $this->app->make('domain_event_publisher');

        // For now we directly connect two bounded contexts, later communication will be handled with RabbitMQ
        $domainEventPublisher->subscribe($this->app->make(UserRegisteredSubscriber::class));
        $domainEventPublisher->subscribe($this->app->make(UserRenamedSubscriber::class));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('domain_event_publisher', DomainEventPublisher::class);
    }
}
