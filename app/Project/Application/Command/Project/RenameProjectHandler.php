<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Project;

use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class RenameProjectHandler extends ProjectHandler
{
    public function handle(RenameProjectCommand $command)
    {
        $teamMemberId = new TeamMemberId($command->owner());

        $project = $this->projectRepository->ofId(new ProjectId($command->projectId()), $teamMemberId);

        $project->rename($command->name(), $teamMemberId);
    }
}
