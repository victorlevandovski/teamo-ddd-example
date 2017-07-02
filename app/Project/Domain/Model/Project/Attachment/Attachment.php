<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Attachment;

use Teamo\Common\Domain\Entity;

class Attachment extends Entity
{
    private $attachmentId;
    private $name;
    private $type;

    public function __construct(AttachmentId $attachmentId, string $name)
    {
        $this->setAttachmentId($attachmentId);
        $this->setName($name);
        $this->setType(AttachmentType::fromName($name));
    }

    private function setAttachmentId(AttachmentId $attachmentId)
    {
        $this->attachmentId = $attachmentId;
    }

    private function setName(string $name)
    {
        $this->name = $name;
    }

    private function setType(AttachmentType $type)
    {
        $this->type = $type;
    }

    public function attachmentId(): AttachmentId
    {
        return $this->attachmentId;
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
