<?php
declare(strict_types=1);

namespace Teamo\User\Domain\Model\User;

use Teamo\Common\Domain\DomainEvent;

class UserRegistered implements DomainEvent
{
    private $occurredOn;
    private $userId;
    private $name;

    public function __construct(UserId $userId, string $name)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
