<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

use Teamo\User\Domain\Model\User\UserId;

class ChangeUserEmailHandler extends UserHandler
{
    public function handle(ChangeUserEmailCommand $command)
    {
        $user = $this->userRepository->ofId(new UserId($command->userId()));

        $user->changeEmail($command->email(), $command->currentPassword());
    }
}
