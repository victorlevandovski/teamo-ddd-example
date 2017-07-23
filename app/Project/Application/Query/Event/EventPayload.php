<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Query\Event;

use Illuminate\Support\Collection;
use Teamo\Project\Application\Query\ProjectPayload;
use Teamo\Project\Domain\Model\Project\Event\Event;
use Teamo\Project\Domain\Model\Project\Event\EventComment;
use Teamo\Project\Domain\Model\Project\Project;

class EventPayload extends ProjectPayload
{
    private $event;
    private $comments;

    public function __construct(Project $project, Event $event, Collection $comments)
    {
        parent::__construct($project);

        $this->event = $event;
        $this->comments = $comments;
    }

    public function event(): Event
    {
        return $this->event;
    }

    /** @return Collection|EventComment[] */
    public function comments(): Collection
    {
        return $this->comments;
    }
}
