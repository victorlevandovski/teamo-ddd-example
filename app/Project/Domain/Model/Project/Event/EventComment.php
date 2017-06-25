<?php

namespace Teamo\Project\Domain\Model\Project\Event;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Project\Comment\Comment;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;

class EventComment extends Comment
{
    private $eventId;

    public function __construct(EventId $eventId, CommentId $commentId, Author $author, $content, Collection $attachments = null)
    {
        parent::__construct($commentId, $author, $content, $attachments);

        $this->setEventId($eventId);
    }

    private function setEventId(EventId $eventId)
    {
        $this->eventId = $eventId;
    }

    public function eventId()
    {
        return $this->eventId;
    }
}
