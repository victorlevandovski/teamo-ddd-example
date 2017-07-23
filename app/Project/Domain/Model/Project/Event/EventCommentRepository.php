<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Event;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;

interface EventCommentRepository
{
    public function add(EventComment $comment);

    public function remove(EventComment $comment);

    public function ofId(CommentId $commentId, EventId $eventId): EventComment;

    /**
     * @param EventId $eventId
     * @return Collection|EventComment[]
     */
    public function all(EventId $eventId): Collection;
}
