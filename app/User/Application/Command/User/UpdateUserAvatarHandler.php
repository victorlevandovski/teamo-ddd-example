<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

use Teamo\User\Domain\Model\User\Avatar;
use Teamo\User\Domain\Model\User\UserId;

class UpdateUserAvatarHandler extends UserHandler
{
    public function handle(UpdateUserAvatarCommand $command)
    {
        $user = $this->userRepository->ofId(new UserId($command->userId()));

        $user->updateAvatar(new Avatar($command->pathTo48pxAvatar(), $command->pathTo96pxAvatar()));
    }
}
