<?php

namespace Teamo\Project\Domain\Model\Project\Event;

use Illuminate\Support\Collection;
use Teamo\Common\Domain\Entity;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Collaborator\Creator;

class Event extends Entity
{
    protected $projectId;
    protected $eventId;
    protected $creator;
    protected $name;
    protected $details;
    protected $startsAt;
    protected $archived;

    public function __construct(ProjectId $projectId, EventId $eventId, Creator $creator, $name, $details, $startsAt)
    {
        $this->setProjectId($projectId);
        $this->setEventId($eventId);
        $this->setCreator($creator);
        $this->setName($name);
        $this->setDetails($details);
        $this->setStartsAt($startsAt);
        $this->setArchived(false);
    }

    private function setProjectId(ProjectId $projectId)
    {
        $this->projectId = $projectId;
    }

    private function setEventId(EventId $eventId)
    {
        $this->eventId = $eventId;
    }

    private function setCreator(Creator $creator)
    {
        $this->creator = $creator;
    }

    private function setName($name)
    {
        $this->name = $name;
    }

    private function setDetails($details)
    {
        $this->details = $details;
    }

    private function setStartsAt($startsAt)
    {
        $this->startsAt = $startsAt;
    }

    private function setArchived($archived)
    {
        $this->archived = $archived;
    }

    /**
     * @return ProjectId
     */
    public function projectId()
    {
        return $this->projectId;
    }

    /**
     * @return EventId
     */
    public function eventId()
    {
        return $this->eventId;
    }

    /**
     * @return Creator
     */
    public function creator()
    {
        return $this->creator;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function details()
    {
        return $this->details;
    }

    /**
     * @return string
     */
    public function startsAt()
    {
        return $this->startsAt;
    }

    /**
     * @return bool
     */
    public function isArchived()
    {
        return $this->archived;
    }

    public function update($name, $details)
    {
        $this->setName($name);
        $this->setDetails($details);
    }

    public function archive()
    {
        $this->archived = true;
    }

    public function restore()
    {
        $this->archived = false;
    }

    public function comment(Author $author, $content, Collection $attachments = null)
    {
        return new EventComment($this->eventId(), new CommentId(), $author, $content, $attachments);
    }
}
