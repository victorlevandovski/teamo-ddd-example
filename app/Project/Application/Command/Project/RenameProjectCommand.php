<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Project;

class RenameProjectCommand
{
    private $ownerId;
    private $projectId;
    private $name;

    public function __construct(string $ownerId, string $projectId, string $name)
    {
        $this->ownerId = $ownerId;
        $this->projectId = $projectId;
        $this->name = $name;
    }

    public function ownerId(): string
    {
        return $this->ownerId;
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
