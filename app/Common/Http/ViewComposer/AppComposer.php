<?php

namespace Teamo\Common\Http\ViewComposer;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Teamo\User\Domain\Model\User\UserId;
use Teamo\User\Domain\Model\User\UserRepository;

class AppComposer
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function compose(View $view)
    {
        if (!Auth::check()) {
            return;
        }

        $user = $this->userRepository->ofId(new UserId(Auth::id()));

        App::setLocale($user->preferences()->language());

        $view->with('userLocale', $user->preferences()->language());
        $view->with('userName', $user->name());
        $view->with('userFirstDayOfWeek', $user->preferences()->firstDayOfWeek());
        $view->with('userDateFormat', $user->preferences()->dateFormat());
    }
}
