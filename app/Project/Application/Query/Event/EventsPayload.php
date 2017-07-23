<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Query\Event;

use Illuminate\Support\Collection;
use Teamo\Project\Application\Query\ProjectPayload;
use Teamo\Project\Domain\Model\Project\Event\Event;
use Teamo\Project\Domain\Model\Project\Project;

class EventsPayload extends ProjectPayload
{
    private $events;

    public function __construct(Project $project, Collection $events)
    {
        parent::__construct($project);

        $this->events = $events;
    }

    /** @return Collection|Event[] */
    public function events(): Collection
    {
        return $this->events;
    }
}
