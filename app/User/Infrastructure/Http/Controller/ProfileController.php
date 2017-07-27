<?php
declare(strict_types=1);

namespace Teamo\User\Infrastructure\Http\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Teamo\Common\Application\CommandBus;
use Teamo\Common\Http\Controller;
use Teamo\User\Application\Command\User\ChangeUserEmailCommand;
use Teamo\User\Application\Command\User\ChangeUserPasswordCommand;
use Teamo\User\Application\Command\User\Exception\InvalidPasswordException;
use Teamo\User\Application\Command\User\RemoveUserAvatarCommand;
use Teamo\User\Application\Command\User\UpdateUserAvatarCommand;
use Teamo\User\Application\Command\User\UpdateUserNotificationSettingsCommand;
use Teamo\User\Application\Command\User\UpdateUserProfileCommand;
use Teamo\User\Domain\Model\User\UserId;
use Teamo\User\Domain\Model\User\UserRepository;
use Teamo\User\Infrastructure\Http\Request\ChangeUserEmailRequest;
use Teamo\User\Infrastructure\Http\Request\ChangeUserPasswordRequest;
use Teamo\User\Infrastructure\Http\Request\UpdateUserProfileRequest;

class ProfileController extends Controller
{
    public function profile(UserRepository $userRepository)
    {
        return view('user.profile.profile', [
            'user' => $userRepository->ofId(new UserId($this->authenticatedId()))
        ]);
    }

    public function update(UpdateUserProfileRequest $request, CommandBus $commandBus)
    {
        $command = new UpdateUserProfileCommand(
            $this->authenticatedId(),
            $request->get('name'),
            $request->get('timezone'),
            $request->get('date_format'),
            (int) $request->get('time_format'),
            (int) $request->get('week_start'),
            $request->get('language')
        );
        $commandBus->handle($command);

        App::setLocale($request->get('language'));

        return redirect(route('user.profile.profile'))->with('success', trans('app.flash_profile_updated'));
    }

    public function editEmail()
    {
        return view('user.profile.email');
    }

    public function updateEmail(ChangeUserEmailRequest $request, CommandBus $commandBus)
    {
        try {
            $command = new ChangeUserEmailCommand($this->authenticatedId(), $request->get('email'), $request->get('password'));
            $commandBus->handle($command);
        } catch (InvalidPasswordException $e) {
            return redirect()
                ->back()
                ->withInput($request->all())
                ->withErrors(['password' => trans('app.flash_invalid_password')]);
        }

        return redirect(route('user.profile.profile'))->with('success', trans('app.flash_email_updated'));
    }

    public function editPassword()
    {
        return view('user.profile.password');
    }

    public function updatePassword(ChangeUserPasswordRequest $request, CommandBus $commandBus)
    {
        try {
            $command = new ChangeUserPasswordCommand($this->authenticatedId(), $request->get('new_password'), $request->get('password'));
            $commandBus->handle($command);
        } catch (InvalidPasswordException $e) {
            return redirect()
                ->back()
                ->withInput($request->all())
                ->withErrors(['password' => trans('app.flash_invalid_password')]);
        }

        return redirect(route('user.profile.profile'))->with('success', trans('app.flash_password_updated'));
    }

    public function editNotifications(UserRepository $userRepository)
    {
        return view('user.profile.notifications', [
            'user' => $userRepository->ofId(new UserId($this->authenticatedId()))
        ]);
    }

    public function updateNotifications(Request $request, CommandBus $commandBus)
    {
        $command = new UpdateUserNotificationSettingsCommand(
            $this->authenticatedId(),
            (bool) $request->get('notify_new_discussion'),
            (bool) $request->get('notify_new_discussion_comment'),
            (bool) $request->get('notify_new_todo'),
            (bool) $request->get('notify_new_todo_comment'),
            (bool) $request->get('notify_new_todo_assigned'),
            (bool) $request->get('notify_new_event'),
            (bool) $request->get('notify_new_event_comment')
        );
        $commandBus->handle($command);

        return redirect(route('user.profile.notifications'))->with('success', trans('app.flash_notifications_settings_saved'));
    }

    public function editAvatar(UserRepository $userRepository)
    {
        return view('user.profile.avatar', [
            'user' => $userRepository->ofId(new UserId($this->authenticatedId()))
        ]);
    }

    public function updateAvatar(Request $request, CommandBus $commandBus)
    {
        if ($request->has('datauri')) {
            $paths = save_avatar($request->get('datauri'), public_path('avatars'), $this->authenticatedId());

            if ($paths) {
                $command = new UpdateUserAvatarCommand($this->authenticatedId(), $paths[48], $paths[96]);
                $commandBus->handle($command);

                return redirect(route('user.profile.profile'))->with('success', trans('app.flash_avatar_saved'));
            }
        }

        return redirect(route('user.profile.avatar'))->with('failure', trans('app.flash_avatar_saving_error'));
    }

    public function deleteAvatar(CommandBus $commandBus)
    {
        $command = new RemoveUserAvatarCommand($this->authenticatedId());
        $commandBus->handle($command);

        remove_avatar($this->authenticatedId());

        return redirect(route('user.profile.profile'))->with('success', trans('app.flash_avatar_removed'));
    }
}
