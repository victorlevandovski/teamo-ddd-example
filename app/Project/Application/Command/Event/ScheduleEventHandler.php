<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Event;

use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\Event\EventRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class ScheduleEventHandler extends EventHandler
{
    private $projectRepository;

    public function __construct(EventRepository $eventRepository, ProjectRepository $projectRepository)
    {
        parent::__construct($eventRepository);

        $this->projectRepository = $projectRepository;
    }

    public function handle(ScheduleEventCommand $command)
    {
        $teamMemberId = new TeamMemberId($command->creator());

        $project = $this->projectRepository->ofId(new ProjectId($command->projectId()), $teamMemberId);

        $event = $project->scheduleEvent(
            new EventId($command->eventId()),
            $teamMemberId,
            $command->name(),
            $command->details(),
            new \DateTimeImmutable($command->occursOn())
        );

        $this->eventRepository->add($event);
    }
}
