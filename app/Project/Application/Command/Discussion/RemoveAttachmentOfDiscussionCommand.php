<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

class RemoveAttachmentOfDiscussionCommand
{
    private $projectId;
    private $discussionId;
    private $attachmentId;
    private $teamMemberId;

    public function __construct(string $projectId, string $discussionId, string $commentId, string $attachmentId, string $teamMemberId)
    {
        $this->projectId = $projectId;
        $this->discussionId = $discussionId;
        $this->attachmentId = $attachmentId;
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

    public function attachmentId(): string
    {
        return $this->attachmentId;
    }

    public function teamMemberId(): string
    {
        return $this->teamMemberId;
    }
}
