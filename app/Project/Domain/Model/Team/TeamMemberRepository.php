<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Team;

interface TeamMemberRepository
{
    public function add(TeamMember $teamMember);

    public function remove(TeamMember $teamMember);

    public function ofId(TeamMemberId $teamMemberId): TeamMember;
}
