<?php
declare(strict_types=1);

namespace Teamo\User\Application\Subscriber;

use Teamo\Common\Domain\DomainEventSubscriber;
use Teamo\Common\Domain\EventStore;

class StoreDomainEventSubscriber implements DomainEventSubscriber
{
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function handle($domainEvent)
    {
        $this->eventStore->append($domainEvent);
    }

    public function isSubscribedTo(string $eventType): bool
    {
        return true;
    }
}
