<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\TodoList;

class PostTodoCommentCommand
{
    private $projectId;
    private $todoListId;
    private $todoId;
    private $author;
    private $commentId;
    private $content;
    private $attachments;

    public function __construct(string $projectId, string $todoListId, string $todoId, string $commentId, string $author, string $content, array $attachments)
    {
        $this->projectId = $projectId;
        $this->todoListId = $todoListId;
        $this->todoId = $todoId;
        $this->commentId = $commentId;
        $this->author = $author;
        $this->content = $content;
        $this->attachments = $attachments;
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

    public function author(): string
    {
        return $this->author;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function attachments(): array
    {
        return $this->attachments;
    }
}
