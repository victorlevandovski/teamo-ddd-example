<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\TodoList;

class RemoveTodoListCommand
{
    private $projectId;
    private $todoListId;
    private $teamMemberId;

    public function __construct(string $projectId, string $todoListId, string $teamMemberId)
    {
        $this->projectId = $projectId;
        $this->todoListId = $todoListId;
        $this->teamMemberId = $teamMemberId;
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
}
