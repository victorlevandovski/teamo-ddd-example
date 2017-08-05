<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\TodoList;

class RenameTodoCommand
{
    private $projectId;
    private $todoListId;
    private $todoId;
    private $teamMemberId;
    private $name;

    public function __construct(string $projectId, string $todoListId, string $todoId, string $teamMemberId, string $name)
    {
        $this->projectId = $projectId;
        $this->todoListId = $todoListId;
        $this->todoId = $todoId;
        $this->teamMemberId = $teamMemberId;
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

    public function teamMemberId(): string
    {
        return $this->teamMemberId;
    }

    public function name(): string
    {
        return $this->name;
    }
}
