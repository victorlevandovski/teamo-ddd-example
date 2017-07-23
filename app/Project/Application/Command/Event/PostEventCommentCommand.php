<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Event;

class PostEventCommentCommand
{
    private $projectId;
    private $eventId;
    private $commentId;
    private $author;
    private $content;
    private $attachments;

    public function __construct(string $projectId, string $eventId, string $commentId, string $author, string $content, array $attachments)
    {
        $this->projectId = $projectId;
        $this->eventId = $eventId;
        $this->commentId = $commentId;
        $this->author = $author;
        $this->content = $content;
        $this->attachments = $attachments;
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

    public function attachments(): array
    {
        return $this->attachments;
    }
}
