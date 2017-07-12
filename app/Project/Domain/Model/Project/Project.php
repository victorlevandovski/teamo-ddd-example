<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Teamo\Common\Domain\Entity;
use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\Event\Event;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoList;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class Project extends Entity
{
    private $ownerId;
    private $projectId;
    private $name;
    private $archived;

    public function __construct(TeamMemberId $ownerId, ProjectId $projectId, string $name)
    {
        $this->setOwnerId($ownerId);
        $this->setProjectId($projectId);
        $this->setName($name);
        $this->setArchived(false);
    }

    public function ownerId(): TeamMemberId
    {
        return $this->ownerId;
    }

    public function projectId(): ProjectId
    {
        return $this->projectId;
    }

    public function name(): string
    {
        return $this->name;
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

    public function startDiscussion(DiscussionId $discussionId, TeamMemberId $authorId, string $topic, string $content, Collection $attachments): Discussion
    {
        return new Discussion($this->projectId(), $discussionId, $authorId, $topic, $content, $attachments);
    }

    public function createTodoList(TodoListId $todoListId, TeamMemberId $creatorId, string $name): TodoList
    {
        return new TodoList($this->projectId(), $todoListId, $creatorId, $name);
    }

    public function scheduleEvent(EventId $eventId, TeamMemberId $creatorId, string $name, string $details, Carbon $startsAt, Collection $attachments): Event
    {
        return new Event($this->projectId(), $eventId, $creatorId, $name, $details, $startsAt, $attachments);
    }

    private function setOwnerId(TeamMemberId $ownerId)
    {
        $this->ownerId = $ownerId;
    }

    private function setProjectId(ProjectId $projectId)
    {
        $this->projectId = $projectId;
    }

    private function setName(string $name)
    {
        $this->assertArgumentNotEmpty($name, 'Project name cannot be empty');

        $this->name = $name;
    }

    private function setArchived(bool $archived)
    {
        $this->archived = $archived;
    }
}
