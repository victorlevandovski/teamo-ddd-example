<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Project;

use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class RestoreProjectHandler extends ProjectHandler
{
    public function handle(RestoreProjectCommand $command)
    {
        $project = $this->projectRepository->ofId(new TeamMemberId($command->ownerId()), new ProjectId($command->projectId()));

        $project->restore();
    }
}
