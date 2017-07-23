<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Event;

use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\ProjectId;

class RemoveEventHandler extends EventHandler
{
    public function handle(RemoveEventCommand $command)
    {
        $event = $this->eventRepository->ofId(new EventId($command->eventId()), new ProjectId($command->projectId()));

        $this->eventRepository->remove($event);
    }
}
