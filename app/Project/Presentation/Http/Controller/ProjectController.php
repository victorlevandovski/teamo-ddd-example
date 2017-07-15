<?php
declare(strict_types=1);

namespace Teamo\Project\Presentation\Http\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Teamo\Common\Application\CommandBus;
use Teamo\Common\Facade\DomainEventPublisher;
use Teamo\Common\Http\Controller;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Teamo\User\Domain\Model\User\UserId;
use Teamo\User\Domain\Model\User\UserRegistered;

class ProjectController extends Controller
{
    public function index(ProjectRepository $projectRepository)
    {
        DomainEventPublisher::publish(new UserRegistered(new UserId('1'), 'user'));

        return view('project.project.index', [
            'projects' => $projectRepository->allOfTeamMember(new TeamMemberId(Auth::id()))
        ]);
    }
}
