<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

class RemoveDiscussionCommentCommand
{
    private $projectId;
    private $discussionId;
    private $commentId;
    private $author;

    public function __construct(string $projectId, string $discussionId, string $commentId, string $author)
    {
        $this->projectId = $projectId;
        $this->discussionId = $discussionId;
        $this->commentId = $commentId;
        $this->author = $author;
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
}
