<?php
declare(strict_types=1);

namespace Teamo\Common\Domain;

use Ramsey\Uuid\Uuid;

abstract class Id
{
    protected $id;

    public function __construct(string $id = null)
    {
        $this->setId($id);
    }

    public function id(): string
    {
        return $this->id;
    }

    protected function setId(string $id = null)
    {
        if (null === $id) {
            $id = Uuid::uuid4()->toString();
        } else if (strlen($id) > 36) {
            throw new \InvalidArgumentException('Id string is too long');
        }

        $this->id = $id;
    }

    public function equalsTo(Id $id): bool
    {
        return $this->id() == $id->id();
    }
}
