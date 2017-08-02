<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\InMemory;

use Illuminate\Support\Collection;
use Teamo\Common\Infrastructure\Persistence\InMemory\InMemoryRepository;
use Teamo\Project\Domain\Model\Project\TodoList\TodoList;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;

class InMemoryTodoListRepository extends InMemoryRepository implements TodoListRepository
{
    public function add(TodoList $todoList)
    {
        $this->items->put($todoList->todoListId()->id(), $todoList);
    }

    public function remove(TodoList $todoList)
    {
        $this->items->forget($todoList->todoListId()->id());
    }

    public function ofId(TodoListId $todoListId, ProjectId $projectId): TodoList
    {
        return $this->findOrFail($todoListId->id(), 'Invalid todo list id');
    }

    public function allActive(ProjectId $projectId): Collection
    {
        return $this->items->filter(function (TodoList $item) use ($projectId) {
            return $item->projectId()->equals($projectId) && !$item->isArchived();
        });
    }

    public function allArchived(ProjectId $projectId): Collection
    {
        return $this->items->filter(function (TodoList $item) use ($projectId) {
            return $item->projectId()->equals($projectId) && $item->isArchived();
        });
    }
}
