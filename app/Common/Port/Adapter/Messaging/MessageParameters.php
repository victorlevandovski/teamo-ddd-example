<?php
declare(strict_types=1);

namespace Teamo\Common\Port\Adapter\Messaging;

class MessageParameters
{
    private $messageId;
    private $type;
    private $timestamp;

    public function __construct(string $messageId, string $type, int $timestamp)
    {
        $this->messageId = $messageId;
        $this->type = $type;
        $this->timestamp = $timestamp;
    }

    public function messageId(): string
    {
        return $this->messageId;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function timestamp(): int
    {
        return $this->timestamp;
    }
}
