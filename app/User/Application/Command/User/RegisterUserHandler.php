<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

use Teamo\User\Domain\Model\User\User;
use Teamo\User\Domain\Model\User\UserId;

class RegisterUserHandler extends UserHandler
{
    public function handle(RegisterUserCommand $command): UserId
    {
        $user = User::register(
            $this->userRepository->nextIdentity(),
            $command->email(),
            $command->password(),
            $command->name(),
            $command->timezone()
        );

        $this->userRepository->add($user);

        return $user->userId();
    }
}
