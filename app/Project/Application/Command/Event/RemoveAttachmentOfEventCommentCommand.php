<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Event;

class RemoveAttachmentOfEventCommentCommand
{
    private $projectId;
    private $eventId;
    private $commentId;
    private $attachmentId;
    private $author;

    public function __construct(string $projectId, string $eventId, string $commentId, string $attachmentId, string $author)
    {
        $this->projectId = $projectId;
        $this->eventId = $eventId;
        $this->commentId = $commentId;
        $this->attachmentId = $attachmentId;
        $this->author = $author;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function commentId(): string
    {
        return $this->commentId;
    }

    public function attachmentId(): string
    {
        return $this->attachmentId;
    }

    public function author(): string
    {
        return $this->author;
    }
}
