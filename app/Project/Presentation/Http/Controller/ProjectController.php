<?php
declare(strict_types=1);

namespace Teamo\Project\Presentation\Http\Controller;

use Illuminate\Support\Facades\Auth;
use Teamo\Common\Http\Controller;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class ProjectController extends Controller
{
    public function index(ProjectRepository $projectRepository)
    {
        return view('project.project.index', [
            'projects' => $projectRepository->allOfTeamMember(new TeamMemberId(Auth::id()))
        ]);
    }
}
