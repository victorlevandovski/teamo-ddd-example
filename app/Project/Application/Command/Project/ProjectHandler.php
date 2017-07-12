<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Project;

use Teamo\Project\Domain\Model\Project\ProjectRepository;

abstract class ProjectHandler
{
    protected $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }
}
