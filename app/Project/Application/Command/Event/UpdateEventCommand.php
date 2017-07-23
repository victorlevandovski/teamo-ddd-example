<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Event;

class UpdateEventCommand
{
    private $projectId;
    private $eventId;
    private $creator;
    private $name;
    private $details;
    private $occursOn;

    public function __construct(string $projectId, string $eventId, string $creator, string $name, string $details, string $occursOn)
    {
        $this->projectId = $projectId;
        $this->eventId = $eventId;
        $this->creator = $creator;
        $this->name = $name;
        $this->details = $details;
        $this->occursOn = $occursOn;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function creator(): string
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

    public function occursOn(): string
    {
        return $this->occursOn;
    }
}
