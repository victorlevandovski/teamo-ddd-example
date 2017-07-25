<?php
declare(strict_types=1);

namespace Teamo\Common\Infrastructure\Persistence;

use Doctrine\ORM\EntityRepository;
use Teamo\Common\Notification\PublishedNotificationTracker;
use Teamo\Common\Notification\PublishedNotificationTrackerStore;

class DoctrinePublishedNotificationTrackerStore extends EntityRepository implements PublishedNotificationTrackerStore
{
    public function publishedNotificationTracker(string $typeName): PublishedNotificationTracker
    {
        $publishedNotificationTracker = $this->findOneByTypeName($typeName);

        if (null === $publishedNotificationTracker) {
            $publishedNotificationTracker = new PublishedNotificationTracker($typeName);
            $this->getEntityManager()->persist($publishedNotificationTracker);
        }

        return $publishedNotificationTracker;
    }
}
