<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

use Illuminate\Contracts\Auth\Guard;
use Teamo\User\Application\Command\User\Exception\InvalidPasswordException;
use Teamo\User\Domain\Model\User\UserId;
use Teamo\User\Domain\Model\User\UserRepository;

class ChangeUserEmailHandler extends UserHandler
{
    private $guard;

    public function __construct(UserRepository $userRepository, Guard $guard)
    {
        parent::__construct($userRepository);

        $this->guard = $guard;
    }

    public function handle(ChangeUserEmailCommand $command)
    {
        $user = $this->userRepository->ofId(new UserId($command->userId()));

        if (!$this->guard->validate(['email' => $user->email(), 'password' => $command->currentPassword()])) {
            throw new InvalidPasswordException();
        }

        $user->changeEmail($command->email());
    }
}
