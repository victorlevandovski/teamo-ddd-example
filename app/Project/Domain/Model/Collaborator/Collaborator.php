<?php

namespace Teamo\Project\Domain\Model\Collaborator;

use Teamo\Common\Domain\ValueObject;

class Collaborator extends ValueObject
{
    protected $id;
    protected $name;

    public function __construct($id, $name)
    {
        $this->setId($id);
        $this->setName($name);
    }

    protected function setId($id)
    {
        $this->id = $id;
    }

    protected function setName($name)
    {
        $this->name = $name;
    }

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }
}
