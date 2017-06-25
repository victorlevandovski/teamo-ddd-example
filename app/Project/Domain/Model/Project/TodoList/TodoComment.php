<?php

namespace Teamo\Project\Domain\Model\Project\TodoList;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Project\Comment\Comment;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;

class TodoComment extends Comment
{
    private $todoId;

    public function __construct(TodoId $todoId, CommentId $commentId, Author $author, $content, Collection $attachments = null)
    {
        parent::__construct($commentId, $author, $content, $attachments);

        $this->setTodoId($todoId);
    }

    private function setTodoId(TodoId $todoId)
    {
        $this->todoId = $todoId;
    }

    public function todoId()
    {
        return $this->todoId;
    }
}
