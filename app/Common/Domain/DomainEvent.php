<?php
declare(strict_types=1);

namespace Teamo\Common\Domain;

interface DomainEvent
{
    public function occurredOn(): \DateTimeImmutable;
}
