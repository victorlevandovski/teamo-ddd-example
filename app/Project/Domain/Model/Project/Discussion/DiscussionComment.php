<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Discussion;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Project\Comment\Comment;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;

class DiscussionComment extends Comment
{
    private $discussionId;

    public function __construct(DiscussionId $discussionId, CommentId $commentId, Author $author, string $content, Collection $attachments = null)
    {
        parent::__construct($commentId, $author, $content, $attachments);

        $this->setDiscussionId($discussionId);
    }

    public function discussionId(): DiscussionId
    {
        return $this->discussionId;
    }

    private function setDiscussionId(DiscussionId $discussionId)
    {
        $this->discussionId = $discussionId;
    }
}
