<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Project;

class RestoreProjectCommand
{
    private $ownerId;
    private $projectId;

    public function __construct(string $ownerId, string $projectId)
    {
        $this->ownerId = $ownerId;
        $this->projectId = $projectId;
    }

    public function ownerId(): string
    {
        return $this->ownerId;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }
}
