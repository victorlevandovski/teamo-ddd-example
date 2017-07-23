<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\Doctrine;

use Illuminate\Support\Collection;
use Teamo\Common\Infrastructure\Persistence\DoctrineRepository;
use Teamo\Project\Domain\Model\Project\Event\Event;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\Event\EventRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;

class DoctrineEventRepository extends DoctrineRepository implements EventRepository
{
    public function add(Event $event)
    {
        $this->getEntityManager()->persist($event);
    }

    public function remove(Event $event)
    {
        $this->getEntityManager()->remove($event);
    }

    public function ofId(EventId $eventId, ProjectId $projectId): Event
    {
        $event = $this->findOneBy(['eventId' => $eventId, 'projectId' => $projectId]);

        if (null === $event) {
            throw new \InvalidArgumentException('Invalid event id or project id');
        }

        return $event;
    }

    public function allActive(ProjectId $projectId): Collection
    {
        return new Collection($this->findBy(['projectId' => $projectId, 'archived' => 0]));
    }

    public function allArchived(ProjectId $projectId): Collection
    {
        return new Collection($this->findBy(['projectId' => $projectId, 'archived' => 1]));
    }
}
