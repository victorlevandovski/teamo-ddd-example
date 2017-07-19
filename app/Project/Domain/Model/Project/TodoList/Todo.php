<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\TodoList;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Teamo\Common\Domain\Entity;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class Todo extends Entity
{
    private $todoListId;
    private $todoId;
    private $name;
    private $assigneeId;
    private $deadline;
    private $completed;

    public function __construct(TodoListId $todoListId, TodoId $todoId, string $name)
    {
        $this->setTodoListId($todoListId);
        $this->setTodoId($todoId);
        $this->setName($name);
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

    public function assigneeId(): ?TeamMemberId
    {
        return $this->assigneeId;
    }

    public function deadline(): ?Carbon
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

    public function uncheck()
    {
        $this->setCompleted(false);
    }

    public function rename(string $name)
    {
        $this->setName($name);
    }

    public function assignTo(TeamMemberId $assigneeId)
    {
        $this->setAssignee($assigneeId);
    }

    public function deadlineOn(Carbon $deadline)
    {
        $this->setDeadline($deadline);
    }

    public function removeAssignee()
    {
        $this->setAssignee(null);
    }

    public function removeDeadline()
    {
        $this->setDeadline(null);
    }

    public function comment(CommentId $commentId, TeamMemberId $author, string $content, Collection $attachments)
    {
        return new TodoComment($this->todoId(), $commentId, $author, $content, $attachments);
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

    private function setAssignee(?TeamMemberId $assigneeId)
    {
        $this->assigneeId = $assigneeId;
    }

    private function setDeadline(?Carbon $deadline)
    {
        $this->deadline = $deadline;
    }

    private function setCompleted(bool $completed)
    {
        $this->completed = $completed;
    }
}
