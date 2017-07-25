<?php
declare(strict_types=1);

namespace Teamo\Common\Notification;

class PublishedNotificationTracker
{
    private $publishedNotificationTrackerId;
    private $mostRecentPublishedNotificationId;
    private $typeName;

    public function __construct(string $typeName)
    {
        $this->mostRecentPublishedNotificationId = 0;
        $this->typeName = $typeName;
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

    public function typeName(): string
    {
        return $this->typeName;
    }
}
