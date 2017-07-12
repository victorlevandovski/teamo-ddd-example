<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Project;

use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class StartNewProjectHandler extends ProjectHandler
{
    public function handle(StartNewProjectCommand $command)
    {
        $project = Project::start(new TeamMemberId($command->ownerId()), new ProjectId($command->projectId()), $command->name());

        $this->projectRepository->add($project);
    }
}
