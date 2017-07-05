<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\TodoList;

use Ramsey\Uuid\Uuid;
use Teamo\Common\Domain\Id;

class TodoId extends Id
{
    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
