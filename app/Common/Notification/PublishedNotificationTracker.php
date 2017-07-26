<?php
declare(strict_types=1);

namespace Teamo\Common\Notification;

class PublishedNotificationTracker
{
    private $publishedNotificationTrackerId;
    private $mostRecentPublishedNotificationId;
    private $exchangeName;

    public function __construct(string $exchangeName)
    {
        $this->mostRecentPublishedNotificationId = 0;
        $this->exchangeName = $exchangeName;
    }

    public function updateMostRecentPublishedNotificationId(int $id)
    {
        $this->mostRecentPublishedNotificationId = $id;
    }

    public function publishedNotificationTrackerId(): int
    {
        return $this->publishedNotificationTrackerId;
    }

    public function mostRecentPublishedNotificationId(): int
    {
        return $this->mostRecentPublishedNotificationId;
    }

    public function exchangeName(): string
    {
        return $this->exchangeName;
    }
}
