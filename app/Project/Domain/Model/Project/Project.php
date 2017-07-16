<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Teamo\Common\Domain\CreatedOn;
use Teamo\Common\Domain\Entity;
use Teamo\Common\Domain\UpdatedOn;
use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\Event\Event;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoList;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Team\TeamMember;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class Project extends Entity
{
    use CreatedOn, UpdatedOn;

    private $owner;
    private $projectId;
    private $name;
    private $archived;
    private $teamMembers;

    public static function start(TeamMember $owningTeamMember, ProjectId $projectId, string $name): self
    {
        $project = new self($owningTeamMember->teamMemberId(), $projectId, $name, false);
        $project->setTeamMembers([$owningTeamMember]);

        return $project;
    }

    public function owner(): TeamMemberId
    {
        return $this->owner;
    }

    public function projectId(): ProjectId
    {
        return $this->projectId;
    }

    public function name(): string
    {
        return $this->name;
    }

    /** @return TeamMember[] */
    public function teamMembers(): array
    {
        return $this->teamMembers;
    }

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function rename(string $name, TeamMemberId $owner)
    {
        $this->assertIsOwner($owner);
        $this->setName($name);
    }

    public function archive(TeamMemberId $owner)
    {
        $this->assertIsOwner($owner);
        $this->setArchived(true);
    }

    public function restore(TeamMemberId $owner)
    {
        $this->assertIsOwner($owner);
        $this->setArchived(false);
    }

    public function addTeamMember(TeamMember $teamMember, TeamMemberId $owner)
    {
        $this->assertIsOwner($owner);

        if (false !== array_search($teamMember, $this->teamMembers())) {
            throw new \InvalidArgumentException('Provided team member is already a project member');
        }

        $this->teamMembers[] = $teamMember;
    }

    public function removeTeamMember(TeamMember $teamMember)
    {
        foreach ($this->teamMembers() as $key => $member) {
            if ($member->teamMemberId()->equals($teamMember->teamMemberId())) {
                unset($this->teamMembers[$key]);
            }
        }
    }

    public function startDiscussion(DiscussionId $discussionId, TeamMemberId $authorId, string $topic, string $content, Collection $attachments): Discussion
    {
        $this->resetUpdatedOn();

        return new Discussion($this->projectId(), $discussionId, $authorId, $topic, $content, $attachments);
    }

    public function createTodoList(TodoListId $todoListId, TeamMemberId $creatorId, string $name): TodoList
    {
        $this->resetUpdatedOn();

        return new TodoList($this->projectId(), $todoListId, $creatorId, $name);
    }

    public function scheduleEvent(EventId $eventId, TeamMemberId $creatorId, string $name, string $details, Carbon $startsAt, Collection $attachments): Event
    {
        $this->resetUpdatedOn();

        return new Event($this->projectId(), $eventId, $creatorId, $name, $details, $startsAt, $attachments);
    }

    public function __construct(TeamMemberId $owner, ProjectId $projectId, string $name, bool $archived)
    {
        $this->setOwner($owner);
        $this->setProjectId($projectId);
        $this->setName($name);
        $this->setArchived($archived);
        $this->setTeamMembers([]);
        $this->resetCreatedOn();
        $this->resetUpdatedOn();
    }

    private function setOwner(TeamMemberId $teamMemberId)
    {
        $this->owner = $teamMemberId;
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

    private function setTeamMembers($teamMembers)
    {
        $this->teamMembers = $teamMembers;
    }

    private function assertIsOwner(TeamMemberId $teamMemberId)
    {
        if (!$this->owner()->equals($teamMemberId)) {
            throw new \InvalidArgumentException('Provided owner does not own this project');
        }
    }
}
