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
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryProjectRepository;
use Tests\TestCase;

class ProjectHandlersTest extends TestCase
{
    /** @var InMemoryProjectRepository */
    private $projectRepository;

    /** @var  Project */
    private $project;

    public function setUp()
    {
        parent::setUp();

        $this->projectRepository = new InMemoryProjectRepository();

        $this->project = Project::start(new TeamMemberId('m-1'), new ProjectId('p-1'), 'My project');
        $this->projectRepository->add($this->project);
    }

    public function testStartNewProjectHandlerAddsProjectToRepository()
    {
        $command = new StartNewProjectCommand('owner-1', 'project-1', 'Project');
        $handler = new StartNewProjectHandler($this->projectRepository);
        $handler->handle($command);

        $project = $this->projectRepository->ofId(new TeamMemberId('owner-1'), new ProjectId('project-1'));

        $this->assertEquals('owner-1', $project->ownerId()->id());
        $this->assertEquals('Project', $project->name());
    }

    public function testRenameProjectHandlerRenamesProject()
    {
        $command = new RenameProjectCommand('m-1', 'p-1', 'New project');
        $handler = new RenameProjectHandler($this->projectRepository);
        $handler->handle($command);

        $project = $this->projectRepository->ofId(new TeamMemberId('m-1'), new ProjectId('p-1'));

        $this->assertEquals('New project', $project->name());
    }

    public function testArchiveProjectHandlerArchivesProject()
    {
        $command = new ArchiveProjectCommand('m-1', 'p-1');
        $handler = new ArchiveProjectHandler($this->projectRepository);
        $handler->handle($command);

        $project = $this->projectRepository->ofId(new TeamMemberId('m-1'), new ProjectId('p-1'));

        $this->assertTrue($project->isArchived());
    }

    public function testRestoreProjectHandlerRestoresProject()
    {
        $command = new RestoreProjectCommand('m-1', 'p-1');
        $handler = new RestoreProjectHandler($this->projectRepository);
        $handler->handle($command);

        $project = $this->projectRepository->ofId(new TeamMemberId('m-1'), new ProjectId('p-1'));

        $this->assertFalse($project->isArchived());
    }
}
