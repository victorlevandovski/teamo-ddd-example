<?php

namespace Teamo\Common\Provider;

use Illuminate\Support\ServiceProvider;
use Teamo\Common\Domain\DomainEventPublisher;
use Teamo\Project\Infrastructure\Messaging\Local\UserRegisteredSubscriber;
use Teamo\Project\Infrastructure\Messaging\Local\UserRenamedSubscriber;
use Teamo\User\Infrastructure\Event\StoreDomainEventSubscriber;

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

        // We directly connect two local bounded contexts
        // Alternatively we can separate them as two applications and communicate via Message Queue
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
