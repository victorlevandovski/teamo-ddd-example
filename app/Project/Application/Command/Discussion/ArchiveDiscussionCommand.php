<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

class ArchiveDiscussionCommand
{
    private $projectId;
    private $discussionId;
    private $teamMemberId;

    public function __construct(string $projectId, string $discussionId, string $teamMemberId)
    {
        $this->projectId = $projectId;
        $this->discussionId = $discussionId;
        $this->teamMemberId = $teamMemberId;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }

    public function discussionId(): string
    {
        return $this->discussionId;
    }

    public function teamMemberId(): string
    {
        return $this->teamMemberId;
    }
}
