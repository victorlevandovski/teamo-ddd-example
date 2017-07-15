<?php
declare(strict_types=1);

namespace Teamo\Common\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void publish(\Teamo\Common\Domain\DomainEvent $domainEvent)
 * @method static void subscribe(string $eventType)
 */
class DomainEventPublisher extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'domain_event_publisher';
    }
}
