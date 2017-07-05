<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project;

use Ramsey\Uuid\Uuid;
use Teamo\Common\Domain\Id;

class ProjectId extends Id
{
    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
