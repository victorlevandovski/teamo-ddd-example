<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Team;

use Teamo\Project\Domain\Model\Team\TeamMemberId;

class RenameTeamMemberHandler extends TeamMemberHandler
{
    public function handle(RenameTeamMemberCommand $command)
    {
        $teamMember = $this->teamMemberRepository->ofId(new TeamMemberId($command->teamMemberId()));
        $teamMember->rename($command->name());
    }
}
