<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

use Teamo\User\Domain\Model\User\User;
use Teamo\User\Domain\Model\User\UserId;

class RegisterUserHandler extends UserHandler
{
    public function handle(RegisterUserCommand $command)
    {
        $user = User::register(
            new UserId($command->userId()),
            $command->email(),
            bcrypt($command->password()),
            $command->name(),
            $command->timezone()
        );

        $this->userRepository->add($user);
    }
}
