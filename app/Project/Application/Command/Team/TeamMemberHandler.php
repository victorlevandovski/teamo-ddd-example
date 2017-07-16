<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Team;

use Teamo\Project\Domain\Model\Team\TeamMemberRepository;

abstract class TeamMemberHandler
{
    protected $teamMemberRepository;

    public function __construct(TeamMemberRepository $teamMemberRepository)
    {
        $this->teamMemberRepository = $teamMemberRepository;
    }
}
