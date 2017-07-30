<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\Doctrine;

use Illuminate\Support\Collection;
use Teamo\Common\Infrastructure\Persistence\DoctrineRepository;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoComment;
use Teamo\Project\Domain\Model\Project\TodoList\TodoCommentRepository;
use Teamo\Project\Domain\Model\Project\TodoList\TodoId;

class DoctrineTodoCommentRepository extends DoctrineRepository implements TodoCommentRepository
{
    public function add(TodoComment $comment)
    {
        $this->getEntityManager()->persist($comment);
    }

    public function remove(TodoComment $comment)
    {
        $this->getEntityManager()->remove($comment);
    }

    public function ofId(CommentId $commentId, TodoId $todoId): TodoComment
    {
        /** @var TodoComment $comment */
        $comment = $this->findOneBy(['commentId' => $commentId, 'todoId' => $todoId]);

        if (null === $comment) {
            throw new \InvalidArgumentException('Invalid comment id or todo id');
        }

        return $comment;
    }

    public function all(TodoId $todoId): Collection
    {
        return new Collection($this->findBy(['todoId' => $todoId]));
    }
}
