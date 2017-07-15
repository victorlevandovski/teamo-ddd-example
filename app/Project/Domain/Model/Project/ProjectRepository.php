<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

interface ProjectRepository
{
    public function add(Project $project);

    public function remove(Project $project);

    public function ofId(ProjectId $projectId, TeamMemberId $teamMemberId): Project;

    /**
     * @param TeamMemberId $teamMemberId
     * @return Collection|Project[]
     */
    public function allOfTeamMember(TeamMemberId $teamMemberId): Collection;
}
