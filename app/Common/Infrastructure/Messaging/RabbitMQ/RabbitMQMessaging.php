<?php
declare(strict_types=1);

namespace Teamo\Common\Infrastructure\Messaging\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

abstract class RabbitMQMessaging
{
    protected $connection;

    /** @var AMQPChannel */
    protected $channel;

    protected $exchangeName;

    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
        $this->channel = null;
        $this->exchangeName = '';
    }

    public function open(string $exchangeName)
    {
        $this->exchangeName = $exchangeName;
        $this->connect();
    }

    public function close()
    {
        if (null !== $this->channel) {
            $this->channel->close();
        }

        $this->connection->close();
    }

    protected function channel()
    {
        return $this->channel;
    }

    private function connect()
    {
        $channel = $this->connection->channel();

        $channel->exchange_declare($this->exchangeName, 'fanout', false, true, false);
        $channel->queue_declare($this->exchangeName, false, true, false, false);
        $channel->queue_bind($this->exchangeName, $this->exchangeName);

        $this->channel = $channel;
    }
}
