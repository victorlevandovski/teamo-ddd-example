<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Attachment;

use Ramsey\Uuid\Uuid;
use Teamo\Common\Domain\Id;

class AttachmentId extends Id
{
    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
