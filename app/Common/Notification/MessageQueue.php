<?php
declare(strict_types=1);

namespace Teamo\Common\Notification;

interface MessageQueue
{
    public function open(string $exchangeName);
    public function close();
    public function send(int $id, string $type, string $body, \DateTimeImmutable $occurredOn);
}
