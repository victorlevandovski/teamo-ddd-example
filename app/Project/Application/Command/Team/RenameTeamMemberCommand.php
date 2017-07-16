<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Team;

class RenameTeamMemberCommand
{
    private $teamMemberId;
    private $name;

    public function __construct(string $teamMemberId, string $name)
    {
        $this->teamMemberId = $teamMemberId;
        $this->name = $name;
    }

    public function teamMemberId(): string
    {
        return $this->teamMemberId;
    }

    public function name(): string
    {
        return $this->name;
    }
}
