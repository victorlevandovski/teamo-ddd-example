<?php
declare(strict_types=1);

namespace Teamo\Common\Port\Adapter\Messaging;

interface MessageProducer
{
    public function open(string $exchangeName);
    public function send(string $message, MessageParameters $messageParameters);
    public function close();
}
