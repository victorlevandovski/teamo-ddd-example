<?php
declare(strict_types=1);

namespace Teamo\Common\Notification;

use Illuminate\Support\Facades\Log;
use Teamo\Common\Domain\EventStore;
use Teamo\Common\Domain\StoredEvent;
use Teamo\Common\Port\Adapter\Messaging\MessageParameters;
use Teamo\Common\Port\Adapter\Messaging\MessageProducer;

class NotificationPublisher
{
    private $eventStore;
    private $publishedNotificationTrackerStore;
    private $messageProducer;
    private $exchangeName;

    public function __construct(
        EventStore $eventStore,
        PublishedNotificationTrackerStore $publishedNotificationTrackerStore,
        MessageProducer $messageProducer,
        string $exchangeName
    ) {
        $this->eventStore = $eventStore;
        $this->publishedNotificationTrackerStore = $publishedNotificationTrackerStore;
        $this->messageProducer = $messageProducer;
        $this->exchangeName = $exchangeName;
    }

    public function publishNotifications(): int
    {
        $publishedNotifications = 0;

        $publishedNotificationTracker = $this->publishedNotificationTracker();
        $notifications = $this->unpublishedNotifications($publishedNotificationTracker);

        if (!$notifications) {
            return $publishedNotifications;
        }

        $this->messageProducer->open($this->exchangeName);

        try {
            foreach ($notifications as $notification) {
                $this->publish($notification);
                $publishedNotificationTracker->updateMostRecentPublishedNotificationId($notification->eventId());
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        $this->messageProducer->close();

        return $publishedNotifications;
    }

    private function publish(StoredEvent $notification)
    {
        $this->messageProducer->send(
            $notification->eventBody(),
            new MessageParameters(
                (string) $notification->eventId(),
                $notification->typeName(),
                $notification->occurredOn()->getTimestamp()
            )
        );
    }

    private function publishedNotificationTracker(): PublishedNotificationTracker
    {
        return $this->publishedNotificationTrackerStore->publishedNotificationTracker($this->exchangeName);
    }

    private function unpublishedNotifications(PublishedNotificationTracker $publishedNotificationTracker)
    {
        return $this->eventStore->allStoredEventsSince($publishedNotificationTracker->mostRecentPublishedNotificationId());
    }
}
