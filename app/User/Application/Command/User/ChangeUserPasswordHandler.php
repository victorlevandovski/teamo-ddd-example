<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

use Teamo\User\Domain\Model\User\UserId;

class ChangeUserPasswordHandler extends UserHandler
{
    public function handle(ChangeUserPasswordCommand $command)
    {
        $user = $this->userRepository->ofId(new UserId($command->userId()));

        $user->changePassword($command->newPassword(), $command->currentPassword());
    }
}
