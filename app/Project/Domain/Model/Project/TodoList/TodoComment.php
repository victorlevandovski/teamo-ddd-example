<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\TodoList;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Comment\Comment;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class TodoComment extends Comment
{
    private $todoId;

    public function __construct(TodoId $todoId, CommentId $commentId, TeamMemberId $authorId, string $content, Collection $attachments)
    {
        parent::__construct($commentId, $authorId, $content, $attachments);

        $this->setTodoId($todoId);
    }

    public function todoId(): TodoId
    {
        return $this->todoId;
    }

    private function setTodoId(TodoId $todoId)
    {
        $this->todoId = $todoId;
    }
}
