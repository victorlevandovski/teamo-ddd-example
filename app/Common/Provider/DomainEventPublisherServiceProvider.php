<?php

namespace Teamo\Common\Provider;

use Illuminate\Support\ServiceProvider;
use Teamo\Common\Domain\DomainEventPublisher;
use Teamo\Project\Infrastructure\Messaging\UserRegisteredListener;
use Teamo\Project\Infrastructure\Messaging\UserRenamedListener;
use Teamo\User\Application\Subscriber\StoreDomainEventSubscriber;

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

        $domainEventPublisher->subscribe($this->app->make(StoreDomainEventSubscriber::class));

        // For now we directly connect two bounded contexts, later communication will be handled by RabbitMQ
        $domainEventPublisher->subscribe($this->app->make(UserRegisteredListener::class));
        $domainEventPublisher->subscribe($this->app->make(UserRenamedListener::class));
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
