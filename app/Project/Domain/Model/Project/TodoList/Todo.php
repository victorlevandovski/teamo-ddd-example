<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\TodoList;

use Illuminate\Support\Collection;
use Teamo\Common\Domain\Entity;
use Teamo\Project\Domain\Model\Collaborator\Assignee;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;

class Todo extends Entity
{
    private $todoListId;
    private $todoId;
    private $name;
    private $assignee;
    private $deadline;
    private $completed;

    public function __construct(TodoListId $todoListId, TodoId $todoId, string $name, Assignee $assignee = null, string $deadline = null)
    {
        $this->setTodoListId($todoListId);
        $this->setTodoId($todoId);
        $this->setName($name);
        $this->setAssignee($assignee);
        $this->setDeadline($deadline);
        $this->setCompleted(false);
    }

    public function todoListId(): TodoListId
    {
        return $this->todoListId;
    }


    public function todoId(): TodoId
    {
        return $this->todoId;
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return Assignee|null
     */
    public function assignee()
    {
        return $this->assignee;
    }

    /**
     * @return string|null
     */
    public function deadline()
    {
        return $this->deadline;
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    public function complete()
    {
        $this->setCompleted(true);
    }

    public function uncomplete()
    {
        $this->setCompleted(false);
    }

    public function update(string $name, Assignee $assignee = null, $deadline = null)
    {
        $this->setName($name);
        $this->setAssignee($assignee);
        $this->setDeadline($deadline);
    }

    public function comment(Author $author, string $content, Collection $attachments = null)
    {
        return new TodoComment($this->todoId(), CommentId::generate(), $author, $content, $attachments);
    }

    private function setTodoListId(TodoListId $todoListId)
    {
        $this->todoListId = $todoListId;
    }

    private function setTodoId(TodoId $todoId)
    {
        $this->todoId = $todoId;
    }

    private function setName(string $name)
    {
        $this->name = $name;
    }

    private function setAssignee(Assignee $assignee = null)
    {
        $this->assignee = $assignee;
    }

    private function setDeadline(string $deadline = null)
    {
        $this->deadline = $deadline;
    }

    private function setCompleted(bool $completed)
    {
        $this->completed = $completed;
    }
}
