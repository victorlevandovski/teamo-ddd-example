<?php
declare(strict_types=1);

namespace Teamo\Common\Domain;

class DomainEventPublisher
{
    /** @var DomainEventSubscriber[] */
    private $subscribers = [];

    public function publish(DomainEvent $domainEvent)
    {
        $eventType = get_class($domainEvent);

        foreach ($this->subscribers as $subscriber) {
            if ($subscriber->isSubscribedTo($eventType)) {
                $subscriber->handle($domainEvent);
            }
        }
    }

    public function subscribe(DomainEventSubscriber $subscriber)
    {
        $this->subscribers[] = $subscriber;
    }
}
