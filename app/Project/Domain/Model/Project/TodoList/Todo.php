<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\TodoList;

use Illuminate\Support\Collection;
use Teamo\Common\Domain\CreatedOn;
use Teamo\Common\Domain\Entity;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class Todo extends Entity
{
    use CreatedOn;

    private $todoListId;
    private $todoId;
    private $creator;
    private $name;
    private $assignee;
    private $deadline;
    private $completed;

    public function __construct(TodoListId $todoListId, TodoId $todoId, TeamMemberId $creator, string $name)
    {
        $this->setTodoListId($todoListId);
        $this->setTodoId($todoId);
        $this->setCreator($creator);
        $this->setName($name);
        $this->setCompleted(false);
        $this->resetCreatedOn();
    }

    public function todoListId(): TodoListId
    {
        return $this->todoListId;
    }
    
    public function todoId(): TodoId
    {
        return $this->todoId;
    }

    public function creator(): TeamMemberId
    {
        return $this->creator;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function assignee(): ?TeamMemberId
    {
        return $this->assignee;
    }

    public function deadline(): ?\DateTimeImmutable
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

    public function assignTo(TeamMemberId $assignee)
    {
        $this->setAssignee($assignee);
    }

    public function deadlineOn(\DateTimeImmutable $deadline)
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

    private function setCreator(TeamMemberId $creator)
    {
        $this->creator = $creator;
    }

    private function setName(string $name)
    {
        $this->name = $name;
    }

    private function setAssignee(?TeamMemberId $assignee)
    {
        $this->assignee = $assignee;
    }

    private function setDeadline(?\DateTimeImmutable $deadline)
    {
        $this->deadline = $deadline;
    }

    private function setCompleted(bool $completed)
    {
        $this->completed = $completed;
    }
}
