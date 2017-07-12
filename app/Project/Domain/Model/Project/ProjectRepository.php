<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

interface ProjectRepository
{
    public function add(Project $project);

    public function remove(Project $project);

    public function ofId(TeamMemberId $teamMemberId, ProjectId $projectId): Project;

    /**
     * @param TeamMemberId $ownerId
     * @return Collection|Project[]
     */
    public function allOwnedBy(TeamMemberId $ownerId): Collection;
}
