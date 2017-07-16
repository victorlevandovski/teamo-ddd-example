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

    public function ofId(ProjectId $projectId, TeamMemberId $teamMemberId): Project
    {
        /** @var Project $project */
        $project = $this->findOrFail($projectId->id(), 'Invalid project id');

        $teamMemberFound = false;
        foreach ($project->teamMembers() as $teamMember) {
            if ($teamMember->teamMemberId()->equals($teamMemberId)) {
                $teamMemberFound = true;
            }
        }
        if (!$teamMemberFound) {
            throw new \InvalidArgumentException('Requesting team member is not a member of requested project');
        }

        return $project;
    }

    public function all(TeamMemberId $teamMemberId): Collection
    {
        return $this->items->filter(function (Project $item) use ($teamMemberId) {
            foreach ($item->teamMembers() as $teamMember) {
                if ($teamMember->teamMemberId()->equals($teamMemberId)) {
                    return true;
                }
            }
            return false;
        });
    }
}
