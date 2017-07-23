<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\InMemory;

use Illuminate\Support\Collection;
use Teamo\Common\Infrastructure\Persistence\InMemory\InMemoryRepository;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Event\EventComment;
use Teamo\Project\Domain\Model\Project\Event\EventCommentRepository;
use Teamo\Project\Domain\Model\Project\Event\EventId;

class InMemoryEventCommentRepository extends InMemoryRepository implements EventCommentRepository
{
    public function add(EventComment $comment)
    {
        $this->items->put($comment->commentId()->id(), $comment);
    }

    public function remove(EventComment $comment)
    {
        $this->items->forget($comment->commentId()->id());
    }

    public function ofId(CommentId $commentId, EventId $eventId): EventComment
    {
        return $this->findOrFail($commentId->id(), 'Invalid comment id');
    }

    public function all(EventId $eventId): Collection
    {
        return $this->items->filter(function (EventComment $item) use ($eventId) {
            return $item->eventId()->equals($eventId);
        });
    }
}
