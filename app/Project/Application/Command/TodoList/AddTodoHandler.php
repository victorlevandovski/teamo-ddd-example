<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\TodoList;

use Teamo\Project\Domain\Model\Project\TodoList\TodoId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class AddTodoHandler extends TodoListHandler
{
    public function handle(AddTodoCommand $command)
    {
        $todoList = $this->todoListRepository->ofId(new TodoListId($command->todoListId()), new ProjectId($command->projectId()));

        $todoId = new TodoId($command->todoId());
        $todoList->addTodo($todoId, new TeamMemberId($command->creator()), $command->name());

        if ($command->assignee()) {
            $todoList->assignTodoTo($todoId, new TeamMemberId($command->assignee()));
        }

        if ($command->deadline()) {
            $todoList->setTodoDeadline($todoId, new \DateTimeImmutable($command->deadline()));
        }
    }
}
