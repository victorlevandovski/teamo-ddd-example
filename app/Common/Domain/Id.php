<?php

namespace Teamo\Common\Domain;

use Ramsey\Uuid\Uuid;

abstract class Id
{
    protected $id;

    public function __construct($id = null)
    {
        $this->setId($id);
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    protected function setId($id)
    {
        if (strlen($id) > 36) {
            throw new \InvalidArgumentException('Id string is too long');
        } else if (null === $id) {
            $id = Uuid::uuid4()->toString();
        }

        $this->id = $id;
    }

    public function equalsTo(Id $id)
    {
        return $this->id() == $id->id();
    }
}
