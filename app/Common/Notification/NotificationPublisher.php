<?php
declare(strict_types=1);

namespace Teamo\Common\Notification;

use Illuminate\Support\Facades\Log;
use Teamo\Common\Domain\EventStore;
use Teamo\Common\Domain\StoredEvent;

class NotificationPublisher
{
    private $eventStore;
    private $publishedNotificationTrackerStore;
    private $messageQueue;
    private $exchangeName;

    public function __construct(
        EventStore $eventStore,
        PublishedNotificationTrackerStore $publishedNotificationTrackerStore,
        MessageQueue $messageQueue,
        string $exchangeName
    ) {
        $this->eventStore = $eventStore;
        $this->publishedNotificationTrackerStore = $publishedNotificationTrackerStore;
        $this->messageQueue = $messageQueue;
        $this->exchangeName = $exchangeName;
    }

    public function publishNotifications(): int
    {
        $publishedNotifications = 0;

        $publishedNotificationTracker = $this->publishedNotificationTrackerStore
            ->publishedNotificationTracker($this->exchangeName);

        $notifications = $this->eventStore
            ->allStoredEventsSince($publishedNotificationTracker->mostRecentPublishedNotificationId());

        if (!$notifications) {
            return $publishedNotifications;
        }

        $this->messageQueue->open($this->exchangeName);

        try {
            foreach ($notifications as $notification) {
                $this->publish($notification);
                $publishedNotificationTracker->updateMostRecentPublishedNotificationId($notification->eventId());
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        $this->messageQueue->close();

        return $publishedNotifications;
    }

    private function publish(StoredEvent $notification)
    {
        $this->messageQueue->send(
            $notification->eventId(),
            $notification->typeName(),
            $notification->eventBody(),
            $notification->occurredOn()
        );
    }
}
