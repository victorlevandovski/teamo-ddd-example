<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\InMemory;

use Teamo\Common\Infrastructure\Persistence\InMemory\InMemoryRepository;
use Teamo\Project\Domain\Model\Team\TeamMember;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Teamo\Project\Domain\Model\Team\TeamMemberRepository;

class InMemoryTeamMemberRepository extends InMemoryRepository implements TeamMemberRepository
{
    public function add(TeamMember $teamMember)
    {
        $this->items->put($teamMember->teamMemberId()->id(), $teamMember);
    }

    public function remove(TeamMember $teamMember)
    {
        $this->items->forget($teamMember->teamMemberId()->id());
    }

    public function ofId(TeamMemberId $teamMemberId): TeamMember
    {
        return $this->findOrFail($teamMemberId->id(), 'Invalid team member id');
    }
}
