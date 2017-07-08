<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

use Teamo\User\Domain\Model\User\UserId;

class RemoveUserAvatarHandler extends UserHandler
{
    public function handle(RemoveUserAvatarCommand $command)
    {
        $user = $this->userRepository->ofId(new UserId($command->userId()));

        $user->removeAvatar();
    }
}
