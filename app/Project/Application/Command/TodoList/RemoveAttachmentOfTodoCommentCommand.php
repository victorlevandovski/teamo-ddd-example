<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\TodoList;

class RemoveAttachmentOfTodoCommentCommand
{
    private $projectId;
    private $todoListId;
    private $todoId;
    private $commentId;
    private $attachmentId;
    private $author;

    public function __construct(string $projectId, string $todoListId, string $todoId, string $commentId, string $attachmentId, string $author)
    {
        $this->projectId = $projectId;
        $this->todoListId = $todoListId;
        $this->todoId = $todoId;
        $this->commentId = $commentId;
        $this->attachmentId = $attachmentId;
        $this->author = $author;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }

    public function todoListId(): string
    {
        return $this->todoListId;
    }

    public function todoId(): string
    {
        return $this->todoId;
    }

    public function commentId(): string
    {
        return $this->commentId;
    }

    public function attachmentId(): string
    {
        return $this->attachmentId;
    }

    public function author(): string
    {
        return $this->author;
    }
}
