<?php
declare(strict_types=1);

namespace Teamo\Common\Domain;

abstract class Id
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

    protected function setId(string $id)
    {
        if (!$id) {
            throw new \InvalidArgumentException('Id string cannot be empty');
        } else if (strlen($id) > 36) {
            throw new \InvalidArgumentException('Id string is too long');
        }

        $this->id = $id;
    }
}
