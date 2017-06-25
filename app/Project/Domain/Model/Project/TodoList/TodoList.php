<?php

namespace Teamo\Project\Domain\Model\Project\TodoList;

use Illuminate\Support\Collection;
use Teamo\Common\Domain\Entity;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Collaborator\Assignee;
use Teamo\Project\Domain\Model\Collaborator\Creator;

class TodoList extends Entity
{
    private $projectId;
    private $todoListId;
    private $creator;
    private $name;
    private $archived;

    /**
     * @var TodoCollection
     */
    private $todos;

    public function __construct(ProjectId $projectId, TodoListId $todoListId, Creator $creator, $name)
    {
        $this->setProjectId($projectId);
        $this->setTodoListId($todoListId);
        $this->setCreator($creator);
        $this->setName($name);
        $this->setArchived(false);

        $this->todos = new TodoCollection();
    }

    private function setProjectId(ProjectId $projectId)
    {
        $this->projectId = $projectId;
    }

    private function setTodoListId(TodoListId $todoListId)
    {
        $this->todoListId = $todoListId;
    }

    private function setCreator(Creator $creator)
    {
        $this->creator = $creator;
    }

    private function setName($name)
    {
        $this->assertArgumentNotEmpty($name, 'Todo List name cannot be empty');

        $this->name = $name;
    }

    private function setArchived($archived)
    {
        $this->archived = $archived;
    }

    /**
     * @return TodoListId
     */
    public function todoListId()
    {
        return $this->todoListId;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return TodoCollection
     */
    public function todos()
    {
        return $this->todos;
    }

    /**
     * @return bool
     */
    public function isArchived()
    {
        return $this->archived;
    }

    public function rename($newName)
    {
        $this->setName($newName);
    }

    public function archive()
    {
        $this->archived = true;
    }

    public function restore()
    {
        $this->archived = false;
    }

    public function addTodo($name, Assignee $assignee = null, $deadline = null)
    {
        $todo = new Todo($this->todoListId(), new TodoId(), $name, $assignee, $deadline);
        $this->todos->put($todo->todoId()->id(), $todo);

        return $todo->todoId();
    }

    public function removeTodo(TodoId $todoId)
    {
        $this->assertTodoExists($todoId);

        $this->todos->forget($todoId->id());
    }

    public function reorderTodo(TodoId $todoId, $position)
    {
        $this->assertTodoExists($todoId);

        $this->todos->reorder($todoId->id(), $position);
    }

    private function assertTodoExists(TodoId $todoId)
    {
        if (!$this->todos->has($todoId->id())) {
            throw new \InvalidArgumentException('Invalid Todo Id');
        }
    }
}
