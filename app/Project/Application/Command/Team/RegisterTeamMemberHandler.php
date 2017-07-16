<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Team;

use Teamo\Project\Domain\Model\Team\TeamMember;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class RegisterTeamMemberHandler extends TeamMemberHandler
{
    public function handle(RegisterTeamMemberCommand $command)
    {
        $teamMember = new TeamMember(
            new TeamMemberId($command->teamMemberId()),
            $command->name()
        );

        $this->teamMemberRepository->add($teamMember);
    }
}
