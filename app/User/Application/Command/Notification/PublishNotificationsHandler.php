<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\Notification;

use Teamo\Common\Domain\EventStore;
use Teamo\Common\Notification\NotificationPublisher;
use Teamo\Common\Notification\PublishedNotificationTrackerStore;
use Teamo\Common\Port\Adapter\Messaging\MessageProducer;

class PublishNotificationsHandler
{
    private $eventStore;
    private $publishedNotificationTrackerStore;
    private $messageProducer;

    public function __construct(
        EventStore $eventStore,
        PublishedNotificationTrackerStore $publishedNotificationTrackerStore,
        MessageProducer $messageProducer
    ) {
        $this->eventStore = $eventStore;
        $this->publishedNotificationTrackerStore = $publishedNotificationTrackerStore;
        $this->messageProducer = $messageProducer;
    }

    public function handle(PublishNotificationsCommand $command)
    {
        $notificationPublisher = new NotificationPublisher(
            $this->eventStore,
            $this->publishedNotificationTrackerStore,
            $this->messageProducer,
            $command->exchangeName()
        );

        $notificationPublisher->publishNotifications();
    }
}
