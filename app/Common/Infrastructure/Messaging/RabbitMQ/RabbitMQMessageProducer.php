<?php
declare(strict_types=1);

namespace Teamo\Common\Infrastructure\Messaging\RabbitMQ;

use PhpAmqpLib\Message\AMQPMessage;
use Teamo\Common\Infrastructure\Messaging\MessageParameters;
use Teamo\Common\Infrastructure\Messaging\MessageProducer;

class RabbitMQMessageProducer extends RabbitMQMessaging implements MessageProducer
{
    public function send(string $message, MessageParameters $messageParameters)
    {
        $properties = [
            'message_id' => $messageParameters->messageId(),
            'type' => $messageParameters->type(),
            'timestamp' => $messageParameters->timestamp()
        ];

        $this->channel()->basic_publish(new AMQPMessage($message, $properties), $this->exchangeName);
    }
}
