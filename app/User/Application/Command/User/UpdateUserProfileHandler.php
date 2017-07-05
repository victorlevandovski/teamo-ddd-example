<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

use Teamo\User\Domain\Model\User\Preferences;
use Teamo\User\Domain\Model\User\UserId;

class UpdateUserProfileHandler extends UserHandler
{
    public function handle(UpdateUserProfileCommand $command)
    {
        $user = $this->userRepository->ofId(new UserId($command->userId()));

        if ($user->name() != $command->name()) {
            $user->rename($command->name());
        }

        $user->updatePreferences(new Preferences(
            $command->language(),
            $command->timezone(),
            $command->dateFormat(),
            $command->timeFormat(),
            $command->firstDayOfWeek(),
            $user->preferences()->showTodoListsAs(),
            $user->preferences()->notifications()
        ));
    }
}
