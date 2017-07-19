<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

class RemoveAttachmentOfDiscussionCommentCommand
{
    private $projectId;
    private $discussionId;
    private $commentId;
    private $attachmentId;
    private $author;

    public function __construct(string $projectId, string $discussionId, string $commentId, string $attachmentId, string $author)
    {
        $this->projectId = $projectId;
        $this->discussionId = $discussionId;
        $this->commentId = $commentId;
        $this->attachmentId = $attachmentId;
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

    public function attachmentId(): string
    {
        return $this->attachmentId;
    }

    public function author(): string
    {
        return $this->author;
    }
}
