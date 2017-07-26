<?php
declare(strict_types=1);

namespace Teamo\Common\Infrastructure\Persistence;

use Doctrine\ORM\EntityRepository;
use Teamo\Common\Notification\PublishedNotificationTracker;
use Teamo\Common\Notification\PublishedNotificationTrackerStore;

class DoctrinePublishedNotificationTrackerStore extends EntityRepository implements PublishedNotificationTrackerStore
{
    public function publishedNotificationTracker(string $exchangeName): PublishedNotificationTracker
    {
        $publishedNotificationTracker = $this->findOneByExchangeName($exchangeName);

        if (null === $publishedNotificationTracker) {
            $publishedNotificationTracker = new PublishedNotificationTracker($exchangeName);
            $this->getEntityManager()->persist($publishedNotificationTracker);
        }

        return $publishedNotificationTracker;
    }
}
