<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\TodoList;

use Teamo\Common\Domain\Entity;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class TodoList extends Entity
{
    private $projectId;
    private $todoListId;
    private $creatorId;
    private $name;
    private $archived;

    /** @var TodoCollection */
    private $todos;

    public function __construct(ProjectId $projectId, TodoListId $todoListId, TeamMemberId $creatorId, string $name)
    {
        $this->setProjectId($projectId);
        $this->setTodoListId($todoListId);
        $this->setCreatorId($creatorId);
        $this->setName($name);
        $this->setArchived(false);
        $this->setTodos(new TodoCollection());
    }

    public function projectId(): ProjectId
    {
        return $this->projectId;
    }

    public function todoListId(): TodoListId
    {
        return $this->todoListId;
    }

    public function creatorId(): TeamMemberId
    {
        return $this->creatorId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function todos(): TodoCollection
    {
        return $this->todos;
    }

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function rename(string $name)
    {
        $this->setName($name);
    }

    public function archive()
    {
        $this->archived = true;
    }

    public function restore()
    {
        $this->archived = false;
    }

    public function addTodo(TodoId $todoId, string $name): TodoId
    {
        $todo = new Todo($this->todoListId(), $todoId, $name);
        $this->todos->put($todo->todoId()->id(), $todo);

        return $todo->todoId();
    }

    public function removeTodo(TodoId $todoId)
    {
        $this->assertTodoExists($todoId);

        $this->todos->forget($todoId->id());
    }

    public function reorderTodo(TodoId $todoId, int $position)
    {
        $this->assertTodoExists($todoId);

        $this->todos->reorder($todoId->id(), $position);
    }

    private function setProjectId(ProjectId $projectId)
    {
        $this->projectId = $projectId;
    }

    private function setTodoListId(TodoListId $todoListId)
    {
        $this->todoListId = $todoListId;
    }

    private function setCreatorId(TeamMemberId $creatorId)
    {
        $this->creatorId = $creatorId;
    }

    private function setName(string $name)
    {
        $this->assertArgumentNotEmpty($name, 'Todo List name cannot be empty');

        $this->name = $name;
    }

    private function setArchived(bool $archived)
    {
        $this->archived = $archived;
    }

    private function setTodos(TodoCollection $todos)
    {
        $this->todos = $todos;
    }

    private function assertTodoExists(TodoId $todoId)
    {
        if (!$this->todos->has($todoId->id())) {
            throw new \InvalidArgumentException('Invalid Todo Id');
        }
    }
}
