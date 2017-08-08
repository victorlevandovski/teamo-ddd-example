<?php

namespace Teamo\Common\Http\ViewComposer;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Teamo\User\Domain\Model\User\UserId;
use Teamo\User\Domain\Model\User\UserRepository;

class AppComposer
{
    protected $userRepository;
    protected $projectRepository;

    public function __construct(UserRepository $userRepository, ProjectRepository $projectRepository)
    {
        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;

    }

    public function compose(View $view)
    {
        if (!Auth::check()) {
            return;
        }

        $user = $this->userRepository->ofId(new UserId((string) Auth::id()));

        App::setLocale($user->preferences()->language());

        $view->with('userLocale', $user->preferences()->language());
        $view->with('userName', $user->name());
        $view->with('userFirstDayOfWeek', $user->preferences()->firstDayOfWeek());
        $view->with('userDateFormat', $user->preferences()->dateFormat());
        $view->with('userTimeFormat', $user->preferences()->timeFormat());
        $view->with('userTimezone', $user->preferences()->timezone());

        if ($route = Route::getCurrentRoute()) {
            if ($route->projectId) {
                $project = $this->projectRepository->ofId(new ProjectId($route->projectId), new TeamMemberId(Auth::id()));
                $view->with('selectedProjectId', $project->projectId()->id());
                $view->with('selectedProjectName', $project->name());
            }
        }
    }
}
