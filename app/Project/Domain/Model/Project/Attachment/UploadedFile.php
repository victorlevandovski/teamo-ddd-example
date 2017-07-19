<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Attachment;

class UploadedFile
{
    private $file;
    private $name;

    public function __construct(string $file, string $name)
    {
        $this->file = $file;
        $this->name = $name;
    }

    public function file(): string
    {
        return $this->file;
    }

    public function name(): string
    {
        return $this->name;
    }
}
