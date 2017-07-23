<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Event;

use Teamo\Project\Domain\Model\Project\Event\EventRepository;

abstract class EventHandler
{
    protected $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }
}
