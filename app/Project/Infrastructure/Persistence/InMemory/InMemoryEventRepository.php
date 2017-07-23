<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\InMemory;

use Illuminate\Support\Collection;
use Teamo\Common\Infrastructure\Persistence\InMemory\InMemoryRepository;
use Teamo\Project\Domain\Model\Project\Event\Event;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\Event\EventRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;

class InMemoryEventRepository extends InMemoryRepository implements EventRepository
{
    public function add(Event $event)
    {
        $this->items->put($event->eventId()->id(), $event);
    }

    public function remove(Event $event)
    {
        $this->items->forget($event->eventId()->id());
    }

    public function ofId(EventId $eventId, ProjectId $projectId): Event
    {
        return $this->findOrFail($eventId->id(), 'Invalid event id');
    }

    public function allActive(ProjectId $projectId): Collection
    {
        return $this->items->filter(function (Event $item) use ($projectId) {
            return $item->projectId()->equals($projectId) && !$item->isArchived();
        });
    }

    public function allArchived(ProjectId $projectId): Collection
    {
        return $this->items->filter(function (Event $item) use ($projectId) {
            return $item->projectId()->equals($projectId) && $item->isArchived();
        });
    }
}
