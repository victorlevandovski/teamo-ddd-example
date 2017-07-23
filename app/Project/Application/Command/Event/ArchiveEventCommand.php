<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Event;

class ArchiveEventCommand
{
    private $projectId;
    private $eventId;
    private $teamMemberId;

    public function __construct(string $projectId, string $eventId, string $teamMemberId)
    {
        $this->projectId = $projectId;
        $this->eventId = $eventId;
        $this->teamMemberId = $teamMemberId;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function teamMemberId(): string
    {
        return $this->teamMemberId;
    }
}
