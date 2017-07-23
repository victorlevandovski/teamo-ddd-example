<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Event;

use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\ProjectId;

class UpdateEventHandler extends EventHandler
{
    public function handle(UpdateEventCommand $command)
    {
        $event = $this->eventRepository->ofId(new EventId($command->eventId()), new ProjectId($command->projectId()));

        $event->update($command->name(), $command->details(), new \DateTimeImmutable($command->occursOn()));
    }
}
