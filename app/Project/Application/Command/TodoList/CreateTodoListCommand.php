<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\TodoList;

class CreateTodoListCommand
{
    private $projectId;
    private $todoListId;
    private $creator;
    private $name;

    public function __construct(string $projectId, string $todoListId, string $creator, string $name)
    {
        $this->projectId = $projectId;
        $this->todoListId = $todoListId;
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

    public function creator(): string
    {
        return $this->creator;
    }

    public function name(): string
    {
        return $this->name;
    }
}
