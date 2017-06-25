<?php

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

    public function __construct(TodoListId $todoListId, TodoId $todoId, $name, Assignee $assignee = null, $deadline = null, $completed = false)
    {
        $this->setTodoListId($todoListId);
        $this->setTodoId($todoId);
        $this->setName($name);
        $this->setAssignee($assignee);
        $this->setDeadline($deadline);
        $this->setCompleted($completed);
    }

    private function setTodoListId(TodoListId $todoListId)
    {
        $this->todoListId = $todoListId;
    }

    private function setTodoId(TodoId $todoId)
    {
        $this->todoId = $todoId;
    }

    private function setName($name)
    {
        $this->name = $name;
    }

    private function setAssignee(Assignee $assignee = null)
    {
        $this->assignee = $assignee;
    }

    private function setDeadline($deadline = null)
    {
        $this->deadline = $deadline;
    }

    private function setCompleted($completed)
    {
        $this->completed = $completed;
    }

    /**
     * @return TodoListId
     */
    public function todoListId()
    {
        return $this->todoListId;
    }

    /**
     * @return TodoId
     */
    public function todoId()
    {
        return $this->todoId;
    }

    /**
     * @return string
     */
    public function name()
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

    /**
     * @return bool
     */
    public function isCompleted()
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

    public function update($name, Assignee $assignee = null, $deadline = null)
    {
        $this->setName($name);
        $this->setAssignee($assignee);
        $this->setDeadline($deadline);
    }

    public function comment(Author $author, $content, Collection $attachments = null)
    {
        return new TodoComment($this->todoId(), new CommentId(), $author, $content, $attachments);
    }
}
