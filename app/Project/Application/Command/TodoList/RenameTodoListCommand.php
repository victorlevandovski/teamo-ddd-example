<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\TodoList;

class RenameTodoListCommand
{
    private $projectId;
    private $todoListId;
    private $teamMemberId;
    private $name;

    public function __construct(string $projectId, string $todoListId, string $teamMemberId, string $name)
    {
        $this->projectId = $projectId;
        $this->todoListId = $todoListId;
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

    public function teamMemberId(): string
    {
        return $this->teamMemberId;
    }

    public function name(): string
    {
        return $this->name;
    }
}
