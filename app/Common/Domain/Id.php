<?php
declare(strict_types=1);

namespace Teamo\Common\Domain;

abstract class Id extends ValueObject
{
    protected $id;

    public function __construct(string $id)
    {
        $this->setId($id);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function equals(Id $id): bool
    {
        return $this->id() == $id->id();
    }

    protected function setId(string $id)
    {
        $this->assertArgumentNotEmpty($id, 'Id string cannot be empty');
        $this->assertArgumentMaxLength($id, 36, 'Id string is too long');

        $this->id = $id;
    }

    public function __toString()
    {
        return $this->id;
    }
}
