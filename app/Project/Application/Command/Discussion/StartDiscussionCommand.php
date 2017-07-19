<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

class StartDiscussionCommand
{
    private $projectId;
    private $discussionId;
    private $author;
    private $topic;
    private $content;
    private $attachments;

    public function __construct(string $projectId, string $discussionId, string $author, string $topic, string $content, array $attachments)
    {
        $this->projectId = $projectId;
        $this->discussionId = $discussionId;
        $this->author = $author;
        $this->topic = $topic;
        $this->content = $content;
        $this->attachments = $attachments;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }

    public function discussionId(): string
    {
        return $this->discussionId;
    }

    public function author(): string
    {
        return $this->author;
    }

    public function topic(): string
    {
        return $this->topic;
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
