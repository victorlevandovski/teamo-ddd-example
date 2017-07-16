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
        return $value->format('Y-m-d H:i:s');
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new \DateTimeImmutable($value);
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
