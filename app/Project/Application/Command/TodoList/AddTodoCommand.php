<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\TodoList;

class AddTodoCommand
{
    private $projectId;
    private $todoListId;
    private $todoId;
    private $creator;
    private $name;

    public function __construct(string $projectId, string $todoListId, string $todoId, string $creator, string $name)
    {
        $this->projectId = $projectId;
        $this->todoListId = $todoListId;
        $this->todoId = $todoId;
        $this->creator = $creator;
        $this->name = $name;
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

    public function creator(): string
    {
        return $this->creator;
    }

    public function name(): string
    {
        return $this->name;
    }
}
