<?php

namespace Teamo\Project\Domain\Model\Project\Attachment;

class AttachmentType
{
    const IMAGE = 'image';
    const FILE = 'file';

    private $type;

    public function __construct($type)
    {
        if (!in_array($type, [self::FILE, self::IMAGE])) {
            throw new \InvalidArgumentException('Invalid attachment type');
        }

        $this->type = $type;
    }

    public function isImage()
    {
        return $this->type == self::IMAGE;
    }

    public function isFile()
    {
        return $this->type == self::FILE;
    }

    public static function fromName($name)
    {
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $type = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']) ? self::IMAGE : self::FILE;

        return new self($type);
    }
}
