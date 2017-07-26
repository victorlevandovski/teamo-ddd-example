<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\Notification;

class PublishNotificationsCommand
{
    private $exchangeName;

    public function __construct(string $exchangeName)
    {
        $this->exchangeName = $exchangeName;
    }

    public function exchangeName(): string
    {
        return $this->exchangeName;
    }
}
