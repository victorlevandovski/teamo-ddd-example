<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Project;

class RestoreProjectCommand
{
    private $owner;
    private $projectId;

    public function __construct(string $owner, string $projectId)
    {
        $this->owner = $owner;
        $this->projectId = $projectId;
    }

    public function owner(): string
    {
        return $this->owner;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }
}
