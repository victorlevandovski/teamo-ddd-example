<?php
declare(strict_types=1);

namespace Teamo\Common\Infrastructure\Persistence;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class DoctrineDateTimeImmutable extends Type
{
    public function getName()
    {
        return 'DateTimeImmutable';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value ? $value->format('Y-m-d H:i:s') : null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value ? new \DateTimeImmutable($value) : null;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDateTimeTypeDeclarationSQL($fieldDeclaration);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
