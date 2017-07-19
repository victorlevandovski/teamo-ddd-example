<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Event;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Comment\Comment;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class EventComment extends Comment
{
    private $eventId;

    public function __construct(EventId $eventId, CommentId $commentId, TeamMemberId $author, string $content, Collection $attachments)
    {
        parent::__construct($commentId, $author, $content, $attachments);

        $this->setEventId($eventId);
    }

    public function eventId(): EventId
    {
        return $this->eventId;
    }

    private function setEventId(EventId $eventId)
    {
        $this->eventId = $eventId;
    }
}
