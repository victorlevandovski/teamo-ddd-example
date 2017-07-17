<?php
declare(strict_types=1);

namespace Teamo\Project\Presentation\Http\Controller;

use Ramsey\Uuid\Uuid;
use Teamo\Common\Application\CommandBus;
use Teamo\Common\Http\Controller;
use Teamo\Project\Application\Command\Project\ArchiveProjectCommand;
use Teamo\Project\Application\Command\Project\RenameProjectCommand;
use Teamo\Project\Application\Command\Project\RestoreProjectCommand;
use Teamo\Project\Application\Command\Project\StartNewProjectCommand;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Teamo\Project\Presentation\Http\Request\RenameProjectRequest;
use Teamo\Project\Presentation\Http\Request\StartProjectRequest;

class ProjectController extends Controller
{
    public function index(ProjectRepository $projectRepository)
    {
        return view('project.project.index', [
            'projects' => $projectRepository->all(new TeamMemberId($this->authenticatedId()))
        ]);
    }

    public function archive(ProjectRepository $projectRepository)
    {
        return view('project.project.archive', [
            'projects' => $projectRepository->all(new TeamMemberId($this->authenticatedId()))
        ]);
    }

    public function show(string $projectId, ProjectRepository $projectRepository)
    {
        return view('project.project.show', [
            'project' => $projectRepository->ofId(new ProjectId($projectId), new TeamMemberId($this->authenticatedId())),
        ]);
    }

    public function create()
    {
        return view('project.project.create');
    }

    public function store(StartProjectRequest $request, CommandBus $commandBus)
    {
        $projectId = Uuid::uuid4()->toString();

        $command = new StartNewProjectCommand($this->authenticatedId(), $projectId, $request->get('name'));
        $commandBus->handle($command);

        return redirect(route('project.project.show', $projectId));
    }

    public function edit(string $projectId, ProjectRepository $projectRepository)
    {
        return view('project.project.edit', [
            'project' => $projectRepository->ofId(new ProjectId($projectId), new TeamMemberId($this->authenticatedId())),
        ]);
    }

    public function update(string $projectId, RenameProjectRequest $request, CommandBus $commandBus)
    {
        $command = new RenameProjectCommand($this->authenticatedId(), $projectId, $request->get('name'));
        $commandBus->handle($command);

        return redirect(route('project.project.show', $projectId));
    }

    public function archiveProject(string $projectId, CommandBus $commandBus)
    {
        $command = new ArchiveProjectCommand($this->authenticatedId(), $projectId);
        $commandBus->handle($command);

        return redirect(route('project.project.index'))->with('success', trans('app.flash_project_moved_to_archive'));
    }

    public function restoreProject(string $projectId, CommandBus $commandBus)
    {
        $command = new RestoreProjectCommand($this->authenticatedId(), $projectId);
        $commandBus->handle($command);

        return redirect(route('project.project.index'))->with('success', trans('app.flash_project_restored'));
    }
}
