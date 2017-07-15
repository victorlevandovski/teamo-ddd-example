<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Project;

class RenameProjectCommand
{
    private $owner;
    private $projectId;
    private $name;

    public function __construct(string $owner, string $projectId, string $name)
    {
        $this->owner = $owner;
        $this->projectId = $projectId;
        $this->name = $name;
    }

    public function owner(): string
    {
        return $this->owner;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }

    public function name(): string
    {
        return $this->name;
    }
}
