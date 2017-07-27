<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Http\Controller;

use Teamo\Common\Http\Controller;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class TeamController extends Controller
{
    public function index(string $projectId, ProjectRepository $projectRepository)
    {
        return view('project.team.index', [
            'project' => $projectRepository->ofId(new ProjectId($projectId), new TeamMemberId($this->authenticatedId())),
            'invites' => collect([]),
        ]);
    }
}
