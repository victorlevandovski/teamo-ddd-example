<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\TodoList;

use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoCommentRepository;
use Teamo\Project\Domain\Model\Project\TodoList\TodoId;

class RemoveTodoCommentHandler
{
    private $commentRepository;

    public function __construct(TodoCommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function handle(RemoveTodoCommentCommand $command)
    {
        $comment = $this->commentRepository->ofId(new CommentId($command->commentId()), new TodoId($command->todoId()));

        $this->commentRepository->remove($comment);
    }
}
