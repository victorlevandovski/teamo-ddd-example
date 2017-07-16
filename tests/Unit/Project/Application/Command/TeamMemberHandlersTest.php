<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Application\Command;

use Teamo\Project\Application\Command\Team\RegisterTeamMemberCommand;
use Teamo\Project\Application\Command\Team\RegisterTeamMemberHandler;
use Teamo\Project\Application\Command\Team\RenameTeamMemberCommand;
use Teamo\Project\Application\Command\Team\RenameTeamMemberHandler;
use Teamo\Project\Domain\Model\Team\TeamMember;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryTeamMemberRepository;
use Tests\TestCase;

class TeamMemberHandlersTest extends TestCase
{
    /** @var InMemoryTeamMemberRepository */
    private $teamMemberRepository;

    public function setUp()
    {
        parent::setUp();

        $this->teamMemberRepository = new InMemoryTeamMemberRepository();
    }

    public function testRegisterTeamMemberHandlerAddsTeamMemberToRepostory()
    {
        $command = new RegisterTeamMemberCommand('team_member-1', 'Team Member');
        $handler = new RegisterTeamMemberHandler($this->teamMemberRepository);
        $handler->handle($command);

        $teamMember = $this->teamMemberRepository->ofId(new TeamMemberId('team_member-1'));

        $this->assertEquals('team_member-1', $teamMember->teamMemberId()->id());
        $this->assertEquals('Team Member', $teamMember->name());
    }

    public function testRenameProjectHandlerRenamesProject()
    {
        $teamMember = new TeamMember(new TeamMemberId('team_member-2'), 'John Doe');
        $this->teamMemberRepository->add($teamMember);

        $command = new RenameTeamMemberCommand('team_member-2', 'Jack Doe');
        $handler = new RenameTeamMemberHandler($this->teamMemberRepository);
        $handler->handle($command);

        $teamMember = $this->teamMemberRepository->ofId(new TeamMemberId('team_member-2'));
        $this->assertEquals('Jack Doe', $teamMember->name());
    }
}
