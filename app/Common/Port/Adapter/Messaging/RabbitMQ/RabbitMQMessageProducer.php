<?php
declare(strict_types=1);

namespace Teamo\Common\Port\Adapter\Messaging\RabbitMQ;

use Teamo\Common\Port\Adapter\Messaging\MessageParameters;
use Teamo\Common\Port\Adapter\Messaging\MessageProducer;

class RabbitMQMessageProducer implements MessageProducer
{
    public function open(string $exchangeName)
    {
        // TODO: Implement open() method.
    }

    public function send(string $message, MessageParameters $messageParameters)
    {
        // TODO: Implement send() method.
    }

    public function close()
    {
        // TODO: Implement close() method.
    }
}
