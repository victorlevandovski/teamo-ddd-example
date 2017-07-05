<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Event;

use Illuminate\Support\Collection;
use Teamo\Common\Domain\Entity;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Project\Attachment\Attachments;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Collaborator\Creator;

class Event extends Entity
{
    use Attachments;

    private $projectId;
    private $eventId;
    private $creator;
    private $name;
    private $details;
    private $startsAt;
    private $archived;

    public function __construct(ProjectId $projectId, EventId $eventId, Creator $creator, $name, $details, $startsAt, Collection $attachments = null)
    {
        $this->setProjectId($projectId);
        $this->setEventId($eventId);
        $this->setCreator($creator);
        $this->setName($name);
        $this->setDetails($details);
        $this->setStartsAt($startsAt);
        $this->setArchived(false);
        $this->setAttachments($attachments);
    }

    public function projectId(): ProjectId
    {
        return $this->projectId;
    }

    public function eventId(): EventId
    {
        return $this->eventId;
    }

    public function creator(): Creator
    {
        return $this->creator;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function details(): string
    {
        return $this->details;
    }

    public function startsAt(): string
    {
        return $this->startsAt;
    }

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function update(string $name, string $details)
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

    public function comment(Author $author, string $content, Collection $attachments = null)
    {
        return new EventComment($this->eventId(), CommentId::generate(), $author, $content, $attachments);
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

    private function setName(string $name)
    {
        $this->name = $name;
    }

    private function setDetails(string $details)
    {
        $this->details = $details;
    }

    private function setStartsAt(string $startsAt)
    {
        $this->startsAt = $startsAt;
    }

    private function setArchived(bool $archived)
    {
        $this->archived = $archived;
    }
}
