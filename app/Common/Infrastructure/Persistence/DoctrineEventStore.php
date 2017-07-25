<?php
declare(strict_types=1);

namespace Teamo\Common\Infrastructure\Persistence;

use Doctrine\ORM\EntityRepository;
use Teamo\Common\Domain\DomainEvent;
use Teamo\Common\Domain\EventStore;
use Teamo\Common\Domain\StoredEvent;
use Teamo\Common\Facade\Serializer;

class DoctrineEventStore extends EntityRepository implements EventStore
{
    public function append(DomainEvent $domainEvent)
    {
        $storedEvent = new StoredEvent(get_class($domainEvent), Serializer::serialize($domainEvent, 'json'), $domainEvent->occurredOn());

        $this->getEntityManager()->persist($storedEvent);
    }

    public function allStoredEventsSince(int $eventId): array
    {
        $query = $this->createQueryBuilder('e');

        if ($eventId) {
            $query->where('e.eventId > :eventId');
            $query->setParameters(['eventId' => $eventId]);
        }

        $query->orderBy('e.eventId');

        return $query->getQuery()->getResult();
    }
}
