<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Team;

use Teamo\Common\Domain\Entity;

class TeamMember extends Entity
{
    private $teamMemberId;
    private $name;

    public function __construct(TeamMemberId $teamMemberId, string $name)
    {
        $this->setTeamMemberId($teamMemberId);
        $this->setName($name);
    }

    public function teamMemberId(): TeamMemberId
    {
        return $this->teamMemberId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function rename($name)
    {
        $this->setName($name);
    }

    private function setTeamMemberId(TeamMemberId $teamMemberId)
    {
        $this->teamMemberId = $teamMemberId;
    }

    private function setName(string $name)
    {
        $this->assertArgumentNotEmpty($name, 'Member name cannot be empty');

        $this->name = $name;
    }
}
