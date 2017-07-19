<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\InMemory;

use Illuminate\Support\Collection;
use Teamo\Common\Infrastructure\Persistence\InMemory\InMemoryRepository;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionComment;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionCommentRepository;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;

class InMemoryDiscussionCommentRepository extends InMemoryRepository implements DiscussionCommentRepository
{
    public function add(DiscussionComment $comment)
    {
        $this->items->put($comment->commentId()->id(), $comment);
    }

    public function remove(DiscussionComment $comment)
    {
        $this->items->forget($comment->commentId()->id());
    }

    public function ofId(CommentId $commentId, DiscussionId $discussionId): DiscussionComment
    {
        return $this->findOrFail($commentId->id(), 'Invalid comment id');
    }

    public function all(DiscussionId $discussionId): Collection
    {
        return $this->items->filter(function (DiscussionComment $item) use ($discussionId) {
            return $item->discussionId()->equals($discussionId);
        });
    }
}
