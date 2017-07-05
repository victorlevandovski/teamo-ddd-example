<?php
declare(strict_types=1);

namespace Teamo\User\Domain\Model\User;

use Ramsey\Uuid\Uuid;
use Teamo\Common\Domain\Id;

class UserId extends Id
{
    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
