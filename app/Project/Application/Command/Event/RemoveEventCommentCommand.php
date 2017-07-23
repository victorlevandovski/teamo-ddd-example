<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Event;

class RemoveEventCommentCommand
{
    private $projectId;
    private $eventId;
    private $commentId;
    private $author;

    public function __construct(string $projectId, string $eventId, string $commentId, string $author)
    {
        $this->projectId = $projectId;
        $this->eventId = $eventId;
        $this->commentId = $commentId;
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

    public function author(): string
    {
        return $this->author;
    }
}
