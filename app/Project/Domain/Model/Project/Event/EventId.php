<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Event;

use Ramsey\Uuid\Uuid;
use Teamo\Common\Domain\Id;

class EventId extends Id
{
    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
