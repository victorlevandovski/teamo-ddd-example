<?php
declare(strict_types=1);

namespace Teamo\User\Presentation\Http\Controller;

use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use Teamo\Common\Application\CommandBus;
use Teamo\Common\Http\Controllers\Controller;
use Teamo\User\Application\Command\User\RegisterUserCommand;
use Teamo\User\Presentation\Http\Request\RegisterUserRequest;

class RegistrationController extends Controller
{
    private $dashboard = '/my';

    public function registration()
    {
        return view('user.registration.register', ['email' => '']);
    }

    public function register(RegisterUserRequest $request, CommandBus $commandBus)
    {
        $userId = Uuid::uuid4()->toString();

        $command = new RegisterUserCommand($userId, $request->get('email'), $request->get('password'), $request->get('name'), $request->get('timezone'));
        $commandBus->handle($command);

        Auth::loginUsingId($userId);

        return redirect($this->dashboard);
    }
}
