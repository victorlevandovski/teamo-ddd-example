<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\TodoList;

use Teamo\Project\Domain\Model\Project\TodoList\TodoId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class UpdateTodoHandler extends TodoListHandler
{
    public function handle(UpdateTodoCommand $command)
    {
        $todoList = $this->todoListRepository->ofId(new TodoListId($command->todoListId()), new ProjectId($command->projectId()));

        $todoId = new TodoId($command->todoId());
        $todo = $todoList->todo($todoId);

        if ($todoList->todo($todoId)->name() != $command->name()) {
            $todoList->renameTodo($todoId, $command->name());
        }

        if ($command->assignee() && (!$todo->assignee() || $todo->assignee()->id() != $command->assignee())) {
            $todoList->assignTodoTo($todoId, new TeamMemberId($command->assignee()));
        } else if ($todo->assignee() && !$command->assignee()) {
            $todoList->removeTodoAssignee($todoId);
        }

        if ($command->deadline() && (!$todo->deadline() || $todo->deadline()->getTimestamp() != strtotime($command->deadline()))) {
            $todoList->setTodoDeadline($todoId, new \DateTimeImmutable($command->deadline()));
        } else if ($todo->deadline() && !$command->deadline()) {
            $todoList->removeTodoDeadline($todoId);
        }
    }
}
