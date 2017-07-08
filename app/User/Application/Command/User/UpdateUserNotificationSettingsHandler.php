<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

use Teamo\User\Domain\Model\User\Notifications;
use Teamo\User\Domain\Model\User\UserId;

class UpdateUserNotificationSettingsHandler extends UserHandler
{
    public function handle(UpdateUserNotificationSettingsCommand $command)
    {
        $user = $this->userRepository->ofId(new UserId($command->userId()));

        $user->updateNotificationSettings(new Notifications(
            $command->whenDiscussionStarted(),
            $command->whenDiscussionCommented(),
            $command->whenTodoListCreated(),
            $command->whenTodoCommented(),
            $command->whenTodoAssignedToMe(),
            $command->whenEventAdded(),
            $command->whenEventCommented()
        ));
    }
}
