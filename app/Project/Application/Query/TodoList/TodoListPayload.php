<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Query\TodoList;

use Teamo\Project\Application\Query\ProjectPayload;
use Teamo\Project\Domain\Model\Project\TodoList\TodoList;
use Teamo\Project\Domain\Model\Project\Project;

class TodoListPayload extends ProjectPayload
{
    private $todoList;

    public function __construct(Project $project, TodoList $todoList)
    {
        parent::__construct($project);

        $this->todoList = $todoList;
    }

    public function todoList(): TodoList
    {
        return $this->todoList;
    }
}
