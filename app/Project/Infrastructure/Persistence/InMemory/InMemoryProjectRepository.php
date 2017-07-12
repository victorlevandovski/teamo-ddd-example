<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\InMemory;

use Illuminate\Support\Collection;
use Teamo\Common\Infrastructure\Persistence\InMemory\InMemoryRepository;
use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class InMemoryProjectRepository extends InMemoryRepository implements ProjectRepository
{
    public function add(Project $project)
    {
        $this->items->put($project->projectId()->id(), $project);
    }

    public function remove(Project $project)
    {
        $this->items->forget($project->projectId()->id());
    }

    public function ofId(TeamMemberId $teamMemberId, ProjectId $projectId): Project
    {
        $project = $this->findOrFail($projectId->id(), 'Invalid project id');

        if (!$project->ownerId()->equals($teamMemberId)) {
            throw new \InvalidArgumentException('This project does not belong to the team of provided team member');
        }

        return $project;
    }

    public function allOwnedBy(TeamMemberId $ownerId): Collection
    {
        return $this->items->filter(function (Project $item) use ($ownerId) {
            return $item->ownerId()->equals($ownerId);
        });
    }
}
