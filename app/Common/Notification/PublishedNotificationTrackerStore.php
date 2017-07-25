<?php
declare(strict_types=1);

namespace Teamo\Common\Notification;

interface PublishedNotificationTrackerStore
{
    public function publishedNotificationTracker(string $typeName): PublishedNotificationTracker;
}
