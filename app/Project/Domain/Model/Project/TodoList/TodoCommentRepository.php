<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\TodoList;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;

interface TodoCommentRepository
{
    public function add(TodoComment $comment);

    public function remove(TodoComment $comment);

    public function ofId(CommentId $commentId, TodoId $todoId): TodoComment;

    /**
     * @param TodoId $todoId
     * @return Collection|TodoComment[]
     */
    public function all(TodoId $todoId): Collection;
}
