<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class DoctrineAttachmentCollection extends Type
{
    public function getName()
    {
        return 'AttachmentCollection';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return serialize($value);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return unserialize($value);
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'TEXT';
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
