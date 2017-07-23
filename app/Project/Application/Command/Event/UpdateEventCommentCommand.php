<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Event;

class UpdateEventCommentCommand
{
    private $projectId;
    private $eventId;
    private $commentId;
    private $author;
    private $content;

    public function __construct(string $projectId, string $eventId, string $commentId, string $author, string $content)
    {
        $this->projectId = $projectId;
        $this->eventId = $eventId;
        $this->commentId = $commentId;
        $this->author = $author;
        $this->content = $content;
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

    public function content(): string
    {
        return $this->content;
    }
}
