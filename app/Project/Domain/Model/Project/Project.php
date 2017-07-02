<?php

namespace Teamo\Project\Domain\Model\Project;

use Illuminate\Support\Collection;
use Teamo\Common\Domain\Entity;
use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\Event\Event;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoList;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Collaborator\Creator;
use Teamo\Project\Domain\Model\Owner\OwnerId;

class Project extends Entity
{
    private $ownerId;
    private $projectId;
    private $name;
    private $archived;

    public function __construct(OwnerId $ownerId, ProjectId $projectId, $name)
    {
        $this->setOwnerId($ownerId);
        $this->setProjectId($projectId);
        $this->setName($name);
        $this->setArchived(false);
    }

    private function setOwnerId(OwnerId $ownerId)
    {
        $this->ownerId = $ownerId;
    }

    private function setProjectId(ProjectId $projectId)
    {
        $this->projectId = $projectId;
    }

    private function setName($name)
    {
        $this->assertArgumentNotEmpty($name, 'Project name cannot be empty');

        $this->name = $name;
    }

    private function setArchived($archived)
    {
        $this->archived = $archived;
    }

    /**
     * @return OwnerId
     */
    public function ownerId()
    {
        return $this->ownerId;
    }

    /**
     * @return ProjectId
     */
    public function projectId()
    {
        return $this->projectId;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
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

    public function startDiscussion(Author $author, $topic, $content, Collection $attachments = null)
    {
        return new Discussion($this->projectId(), new DiscussionId(), $author, $topic, $content, $attachments);
    }

    public function createTodoList(Creator $creator, $name)
    {
        return new TodoList($this->projectId(), new TodoListId(), $creator, $name);
    }

    public function scheduleEvent(Creator $creator, $name, $details, $startsAt, Collection $attachments = null)
    {
        return new Event($this->projectId(), new EventId(), $creator, $name, $details, $startsAt, $attachments);
    }
}
