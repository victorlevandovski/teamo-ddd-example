<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\InMemory;

use Illuminate\Support\Collection;
use Teamo\Common\Infrastructure\Persistence\InMemory\InMemoryRepository;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoComment;
use Teamo\Project\Domain\Model\Project\TodoList\TodoCommentRepository;
use Teamo\Project\Domain\Model\Project\TodoList\TodoId;

class InMemoryTodoCommentRepository extends InMemoryRepository implements TodoCommentRepository
{
    public function add(TodoComment $comment)
    {
        $this->items->put($comment->commentId()->id(), $comment);
    }

    public function remove(TodoComment $comment)
    {
        $this->items->forget($comment->commentId()->id());
    }

    public function ofId(CommentId $commentId, TodoId $todoId): TodoComment
    {
        return $this->findOrFail($commentId->id(), 'Invalid comment id');
    }

    public function all(TodoId $todoId): Collection
    {
        return $this->items->filter(function (TodoComment $item) use ($todoId) {
            return $item->todoId()->equals($todoId);
        });
    }
}
