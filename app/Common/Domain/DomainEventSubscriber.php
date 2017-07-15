<?php
declare(strict_types=1);

namespace Teamo\Common\Domain;

interface DomainEventSubscriber
{
    /** @param DomainEvent $domainEvent */
    public function handle($domainEvent);

    public function isSubscribedTo(string $eventType): bool;
}
