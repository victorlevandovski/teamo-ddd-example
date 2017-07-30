<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\TodoList;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\ProjectId;

interface TodoListRepository
{
    public function add(TodoList $todoList);

    public function remove(TodoList $todoList);

    public function ofId(TodoListId $todoListId, ProjectId $projectId): TodoList;

    /**
     * @param ProjectId $projectId
     * @return Collection|TodoList[]
     */
    public function allActive(ProjectId $projectId): Collection;

    /**
     * @param ProjectId $projectId
     * @return Collection|TodoList[]
     */
    public function allArchived(ProjectId $projectId): Collection;
}
