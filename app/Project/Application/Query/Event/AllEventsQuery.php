<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Query\Event;

class AllEventsQuery
{
    private $projectId;
    private $teamMemberId;
    private $archived;

    public function __construct(string $projectId, string $teamMemberId, bool $archived)
    {
        $this->projectId = $projectId;
        $this->teamMemberId = $teamMemberId;
        $this->archived = $archived;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }

    public function teamMemberId(): string
    {
        return $this->teamMemberId;
    }

    public function archived(): bool
    {
        return $this->archived;
    }
}
