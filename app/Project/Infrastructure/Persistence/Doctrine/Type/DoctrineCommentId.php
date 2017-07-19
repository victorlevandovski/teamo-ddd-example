<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;

class DoctrineCommentId extends GuidType
{
    public function getName()
    {
        return 'CommentId';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->id();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new CommentId($value);
    }
}
