<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\TodoList;

use Doctrine\Common\Collections\ArrayCollection;
use Teamo\Common\Domain\CreatedOn;
use Teamo\Common\Domain\Entity;
use Teamo\Common\Domain\UpdatedOn;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class TodoList extends Entity
{
    use CreatedOn;

    private $projectId;
    private $todoListId;
    private $creator;
    private $name;
    private $archived;

    /** @var ArrayCollection|Todo[] */
    private $todos;

    public function __construct(ProjectId $projectId, TodoListId $todoListId, TeamMemberId $creator, string $name)
    {
        $this->setProjectId($projectId);
        $this->setTodoListId($todoListId);
        $this->setCreator($creator);
        $this->setName($name);
        $this->setArchived(false);
        $this->setTodos(new ArrayCollection());
        $this->resetCreatedOn();
    }

    public function projectId(): ProjectId
    {
        return $this->projectId;
    }

    public function todoListId(): TodoListId
    {
        return $this->todoListId;
    }

    public function creator(): TeamMemberId
    {
        return $this->creator;
    }

    public function name(): string
    {
        return $this->name;
    }

    /** @return Todo[] */
    public function todos(): array
    {
        return $this->todos->toArray();
    }

    public function todo(TodoId $todoId): Todo
    {
        foreach ($this->todos as $todo) {
            if ($todo->todoId()->equals($todoId)) {
                return $todo;
            }
        }

        throw new \InvalidArgumentException('Invalid todo Id');
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

    public function addTodo(TodoId $todoId, TeamMemberId $creator, string $name)
    {
        $todo = new Todo($this->todoListId(), $todoId, $creator, $name, count($this->todos) + 1);

        $this->todos[] = $todo;
    }

    public function removeTodo(TodoId $todoId)
    {
        foreach ($this->todos as $key => $todo) {
            if ($todo->todoId()->equals($todoId)) {
                unset($this->todos[$key]);
                break;
            }
        }
    }

    public function reorderTodo(TodoId $todoId, int $position)
    {
        $previousPosition = $this->todo($todoId)->position();

        foreach ($this->todos as $todo) {
            $todo->reorder($todoId, $position, $previousPosition);
        }
    }

    public function renameTodo(TodoId $todoId, string $name)
    {
        $this->todo($todoId)->rename($name);
    }

    public function completeTodo(TodoId $todoId)
    {
        $this->todo($todoId)->complete();
    }

    public function uncheckTodo(TodoId $todoId)
    {
        $this->todo($todoId)->uncheck();
    }

    public function assignTodoTo(TodoId $todoId, TeamMemberId $assignee)
    {
        $this->todo($todoId)->assignTo($assignee);
    }

    public function addDeadlineToTodo(TodoId $todoId, \DateTimeImmutable $deadline)
    {
        $this->todo($todoId)->deadlineOn($deadline);
    }

    public function removeTodoAssignee(TodoId $todoId)
    {
        $this->todo($todoId)->removeAssignee();
    }

    public function removeTodoDeadline(TodoId $todoId)
    {
        $this->todo($todoId)->removeDeadline();
    }

    private function setProjectId(ProjectId $projectId)
    {
        $this->projectId = $projectId;
    }

    private function setTodoListId(TodoListId $todoListId)
    {
        $this->todoListId = $todoListId;
    }

    private function setCreator(TeamMemberId $creator)
    {
        $this->creator = $creator;
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

    private function setTodos(ArrayCollection $todos)
    {
        $this->todos = $todos;
    }
}
