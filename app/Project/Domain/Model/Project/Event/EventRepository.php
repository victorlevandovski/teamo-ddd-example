<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Event;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\ProjectId;

interface EventRepository
{
    public function add(Event $event);

    public function remove(Event $event);

    public function ofId(EventId $eventId, ProjectId $projectId): Event;

    /**
     * @param ProjectId $projectId
     * @return Collection|Event[]
     */
    public function allActive(ProjectId $projectId): Collection;

    /**
     * @param ProjectId $projectId
     * @return Collection|Event[]
     */
    public function allArchived(ProjectId $projectId): Collection;
}
