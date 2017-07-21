<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Query;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Team\TeamMember;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class ProjectPayload
{
    protected $project;

    /** @var Collection */
    protected $teamMembers;

    public function __construct(Project $project)
    {
        $this->project = $project;

        $this->setTeamMembers($project->teamMembers());
    }

    public function project(): Project
    {
        return $this->project;
    }

    public function teamMember(TeamMemberId $teamMemberId): TeamMember
    {
        return $this->teamMembers->get($teamMemberId->id());
    }

    protected function setTeamMembers(array $teamMembers)
    {
        $this->teamMembers = new Collection();

        foreach ($teamMembers as $teamMember) {
            $this->teamMembers->put($teamMember->teamMemberId()->id(), $teamMember);
        }
    }
}
