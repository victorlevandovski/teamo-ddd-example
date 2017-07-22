<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Attachment;

use Teamo\Common\Domain\Entity;

class Attachment extends Entity
{
    private $id;
    private $name;
    private $type;

    public function __construct(string $id, string $name)
    {
        $this->setId($id);
        $this->setName($name);
        $this->setType(AttachmentType::fromName($name));
    }

    private function setId(string $id)
    {
        $this->id = $id;
    }

    private function setName(string $name)
    {
        $this->name = $name;
    }

    private function setType(AttachmentType $type)
    {
        $this->type = $type;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): AttachmentType
    {
        return $this->type;
    }
}
