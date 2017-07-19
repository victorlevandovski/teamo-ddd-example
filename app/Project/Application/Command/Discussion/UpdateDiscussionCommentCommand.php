<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

class UpdateDiscussionCommentCommand
{
    private $projectId;
    private $discussionId;
    private $author;
    private $commentId;
    private $content;

    public function __construct(string $projectId, string $discussionId, string $commentId, string $author, string $content)
    {
        $this->projectId = $projectId;
        $this->discussionId = $discussionId;
        $this->commentId = $commentId;
        $this->author = $author;
        $this->content = $content;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }

    public function discussionId(): string
    {
        return $this->discussionId;
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
