<?php
declare(strict_types=1);

namespace Teamo\Common\Domain;

use Teamo\Common\Facade\Serializer;

class StoredEvent
{
    private $eventId;
    private $typeName;
    private $eventBody;
    private $occurredOn;

    public function __construct(string $typeName, string $eventBody, \DateTimeImmutable $occurredOn)
    {
        $this->typeName = $typeName;
        $this->eventBody = $eventBody;
        $this->occurredOn = $occurredOn;
    }

    public function eventId(): int
    {
        return $this->eventId;
    }

    public function typeName(): string
    {
        return $this->typeName;
    }

    public function eventBody(): string
    {
        return $this->eventBody;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function toDomainEvent(): DomainEvent
    {
        return Serializer::deserialize($this->eventBody, $this->typeName, 'json');
    }
}
