<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Application\Command;

use Teamo\Project\Application\Command\Project\ArchiveProjectCommand;
use Teamo\Project\Application\Command\Project\ArchiveProjectHandler;
use Teamo\Project\Application\Command\Project\RenameProjectCommand;
use Teamo\Project\Application\Command\Project\RenameProjectHandler;
use Teamo\Project\Application\Command\Project\RestoreProjectCommand;
use Teamo\Project\Application\Command\Project\RestoreProjectHandler;
use Teamo\Project\Application\Command\Project\StartNewProjectCommand;
use Teamo\Project\Application\Command\Project\StartNewProjectHandler;
use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMember;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryTeamMemberRepository;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryProjectRepository;
use Tests\TestCase;

class ProjectHandlersTest extends TestCase
{
    /** @var InMemoryProjectRepository */
    private $projectRepository;

    /** @var InMemoryTeamMemberRepository */
    private $teamMemberRepository;

    /** @var  Project */
    private $project;

    public function setUp()
    {
        parent::setUp();

        $this->projectRepository = new InMemoryProjectRepository();
        $this->teamMemberRepository = new InMemoryTeamMemberRepository();

        $owner = new TeamMemberId('test-project-owner');
        $teamMember = new TeamMember($owner, 'John Doe');
        $this->teamMemberRepository->add($teamMember);

        $this->project = Project::start($teamMember, new ProjectId('test-project'), 'My project');
        $this->project->addTeamMember(new TeamMember(new TeamMemberId('test-team-member'), 'Jane Doe'), $owner);
        $this->projectRepository->add($this->project);
    }

    public function testStartNewProjectHandlerAddsProjectToRepository()
    {
        $command = new StartNewProjectCommand('test-project-owner', 'project-1', 'Project');
        $handler = new StartNewProjectHandler($this->projectRepository, $this->teamMemberRepository);
        $handler->handle($command);

        $project = $this->projectRepository->ofId(new ProjectId('project-1'), new TeamMemberId('test-project-owner'));

        $this->assertEquals('test-project-owner', $project->owner()->id());
        $this->assertEquals('Project', $project->name());
    }

    public function testRenameProjectHandlerRenamesProject()
    {
        $command = new RenameProjectCommand('test-project-owner', 'test-project', 'New project');
        $handler = new RenameProjectHandler($this->projectRepository);
        $handler->handle($command);

        $project = $this->projectRepository->ofId(new ProjectId('test-project'), new TeamMemberId('test-project-owner'));
        $this->assertEquals('New project', $project->name());

        $command = new RenameProjectCommand('test-team-member', 'test-project', 'Wrong member project');
        $this->expectException(\InvalidArgumentException::class);
        $handler->handle($command);
    }

    public function testArchiveProjectHandlerArchivesProject()
    {
        $command = new ArchiveProjectCommand('test-project-owner', 'test-project');
        $handler = new ArchiveProjectHandler($this->projectRepository);
        $handler->handle($command);

        $project = $this->projectRepository->ofId(new ProjectId('test-project'), new TeamMemberId('test-project-owner'));
        $this->assertTrue($project->isArchived());

        $command = new ArchiveProjectCommand('test-team-member', 'test-project');
        $this->expectException(\InvalidArgumentException::class);
        $handler->handle($command);
    }

    public function testRestoreProjectHandlerRestoresProject()
    {
        $command = new RestoreProjectCommand('test-project-owner', 'test-project');
        $handler = new RestoreProjectHandler($this->projectRepository);
        $handler->handle($command);

        $project = $this->projectRepository->ofId(new ProjectId('test-project'), new TeamMemberId('test-project-owner'));
        $this->assertFalse($project->isArchived());

        $command = new RestoreProjectCommand('test-team-member', 'test-project');
        $this->expectException(\InvalidArgumentException::class);
        $handler->handle($command);
    }
}
