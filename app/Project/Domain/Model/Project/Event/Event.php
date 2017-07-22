<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Event;

use Illuminate\Support\Collection;
use Teamo\Common\Domain\CreatedOn;
use Teamo\Common\Domain\Entity;
use Teamo\Project\Domain\Model\Project\Attachment\Attachments;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class Event extends Entity
{
    use CreatedOn;
    use Attachments;

    private $projectId;
    private $eventId;
    private $creator;
    private $name;
    private $details;
    private $occursOn;
    private $archived;

    public function __construct(ProjectId $projectId, EventId $eventId, TeamMemberId $creator, string $name, string $details, \DateTimeImmutable $occursOn, Collection $attachments)
    {
        $this->setProjectId($projectId);
        $this->setEventId($eventId);
        $this->setCreator($creator);
        $this->setName($name);
        $this->setDetails($details);
        $this->setOccursOn($occursOn);
        $this->setArchived(false);
        $this->setAttachments($attachments);
        $this->resetCreatedOn();
    }

    public function projectId(): ProjectId
    {
        return $this->projectId;
    }

    public function eventId(): EventId
    {
        return $this->eventId;
    }

    public function creator(): TeamMemberId
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

    public function occursOn(): \DateTimeImmutable
    {
        return $this->occursOn;
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

    public function comment(CommentId $commentId, TeamMemberId $author, string $content, Collection $attachments)
    {
        return new EventComment($this->eventId(), $commentId, $author, $content, $attachments);
    }

    private function setProjectId(ProjectId $projectId)
    {
        $this->projectId = $projectId;
    }

    private function setEventId(EventId $eventId)
    {
        $this->eventId = $eventId;
    }

    private function setCreator(TeamMemberId $creator)
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

    private function setOccursOn(\DateTimeImmutable $occursOn)
    {
        $this->occursOn = $occursOn;
    }

    private function setArchived(bool $archived)
    {
        $this->archived = $archived;
    }
}
