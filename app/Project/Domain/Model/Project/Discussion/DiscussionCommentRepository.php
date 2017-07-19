<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Discussion;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;

interface DiscussionCommentRepository
{
    public function add(DiscussionComment $comment);

    public function remove(DiscussionComment $comment);

    public function ofId(CommentId $commentId, DiscussionId $discussionId): DiscussionComment;

    /**
     * @param DiscussionId $discussionId
     * @return Collection|DiscussionComment[]
     */
    public function all(DiscussionId $discussionId): Collection;
}
