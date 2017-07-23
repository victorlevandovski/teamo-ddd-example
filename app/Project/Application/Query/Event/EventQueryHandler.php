<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Query\Event;

use Teamo\Project\Domain\Model\Project\Event\EventCommentRepository;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\Event\EventRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class EventQueryHandler
{
    private $projectRepository;
    private $eventRepository;
    private $commentRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        EventRepository $eventRepository,
        EventCommentRepository $commentRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->eventRepository = $eventRepository;
        $this->commentRepository = $commentRepository;
    }

    public function event(EventQuery $query): EventPayload
    {
        $projectId = new ProjectId($query->projectId());
        $eventId = new EventId($query->eventId());

        return new EventPayload(
            $this->projectRepository->ofId($projectId, new TeamMemberId($query->teamMemberId())),
            $this->eventRepository->ofId($eventId, $projectId),
            $this->commentRepository->all($eventId)
        );
    }

    public function allEvents(AllEventsQuery $query): EventsPayload
    {
        $projectId = new ProjectId($query->projectId());

        if (!$query->archived()) {
            $events = $this->eventRepository->allActive($projectId);
        } else {
            $events = $this->eventRepository->allArchived($projectId);
        }

        return new EventsPayload(
            $this->projectRepository->ofId($projectId, new TeamMemberId($query->teamMemberId())),
            $events
        );
    }
}
