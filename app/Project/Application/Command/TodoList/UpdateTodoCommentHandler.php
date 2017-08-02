<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\TodoList;

use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoCommentRepository;
use Teamo\Project\Domain\Model\Project\TodoList\TodoId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class UpdateTodoCommentHandler
{
    private $commentRepository;

    public function __construct(TodoCommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function handle(UpdateTodoCommentCommand $command)
    {
        $comment = $this->commentRepository->ofId(new CommentId($command->commentId()), new TodoId($command->todoId()));

        $comment->update($command->content(), new TeamMemberId($command->author()));
    }
}
