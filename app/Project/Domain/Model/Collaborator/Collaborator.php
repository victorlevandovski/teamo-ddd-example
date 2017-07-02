<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Collaborator;

use Teamo\Common\Domain\ValueObject;

class Collaborator extends ValueObject
{
    protected $id;
    protected $name;

    public function __construct(string $id, string $name)
    {
        $this->setId($id);
        $this->setName($name);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    protected function setId(string $id)
    {
        $this->id = $id;
    }

    protected function setName(string $name)
    {
        $this->name = $name;
    }
}
