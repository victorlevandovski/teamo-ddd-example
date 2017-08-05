<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\TodoList;

use Teamo\Project\Domain\Model\Project\TodoList\TodoId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Project\ProjectId;

class SetTodoDeadlineHandler extends TodoListHandler
{
    public function handle(SetTodoDeadlineCommand $command)
    {
        $todoList = $this->todoListRepository->ofId(new TodoListId($command->todoListId()), new ProjectId($command->projectId()));

        $todoList->setTodoDeadline(new TodoId($command->todoId()), new \DateTimeImmutable($command->deadline()));
    }
}
