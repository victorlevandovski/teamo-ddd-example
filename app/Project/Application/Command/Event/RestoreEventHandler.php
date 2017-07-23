<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Event;

use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\ProjectId;

class RestoreEventHandler extends EventHandler
{
    public function handle(RestoreEventCommand $command)
    {
        $event = $this->eventRepository->ofId(new EventId($command->eventId()), new ProjectId($command->projectId()));

        $event->restore();
    }
}
