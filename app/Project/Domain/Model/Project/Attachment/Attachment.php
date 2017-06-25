<?php

namespace Teamo\Project\Domain\Model\Project\Attachment;

use Teamo\Common\Domain\Entity;

class Attachment extends Entity
{
    private $attachmentId;
    private $name;
    private $type;

    public function __construct(AttachmentId $attachmentId, $name)
    {
        $this->setAttachmentId($attachmentId);
        $this->setName($name);
        $this->setType(AttachmentType::fromName($name));
    }

    private function setAttachmentId(AttachmentId $attachmentId)
    {
        $this->attachmentId = $attachmentId;
    }

    private function setName($name)
    {
        $this->name = $name;
    }

    private function setType(AttachmentType $type)
    {
        $this->type = $type;
    }

    /**
     * @return AttachmentId
     */
    public function attachmentId()
    {
        return $this->attachmentId;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return AttachmentType
     */
    public function type()
    {
        return $this->type;
    }
}
