<?php

namespace Teamo\Common\Provider;

use Illuminate\Support\ServiceProvider;
use Teamo\Common\Application\CommandBus;
use Teamo\Common\Application\SimpleCommandBus;

class CommandBusServiceProvider extends ServiceProvider
{
    private function mappings(): array
    {
        return [
            // User
            \Teamo\User\Application\Command\User\RegisterUserCommand::class => \Teamo\User\Application\Command\User\RegisterUserHandler::class,
            \Teamo\User\Application\Command\User\UpdateUserProfileCommand::class => \Teamo\User\Application\Command\User\UpdateUserProfileHandler::class,
            \Teamo\User\Application\Command\User\UpdateUserNotificationSettingsCommand::class => \Teamo\User\Application\Command\User\UpdateUserNotificationSettingsHandler::class,
            \Teamo\User\Application\Command\User\ChangeUserEmailCommand::class => \Teamo\User\Application\Command\User\ChangeUserEmailHandler::class,
            \Teamo\User\Application\Command\User\ChangeUserPasswordCommand::class => \Teamo\User\Application\Command\User\ChangeUserPasswordHandler::class,
            \Teamo\User\Application\Command\User\UpdateUserAvatarCommand::class => \Teamo\User\Application\Command\User\UpdateUserAvatarHandler::class,
            \Teamo\User\Application\Command\User\RemoveUserAvatarCommand::class => \Teamo\User\Application\Command\User\RemoveUserAvatarHandler::class,

            // User notifications publisher
            \Teamo\User\Application\Command\Notification\PublishNotificationsCommand::class => \Teamo\User\Application\Command\Notification\PublishNotificationsHandler::class,

            // TeamMember
            \Teamo\Project\Application\Command\Team\RegisterTeamMemberCommand::class => \Teamo\Project\Application\Command\Team\RegisterTeamMemberHandler::class,
            \Teamo\Project\Application\Command\Team\RenameTeamMemberCommand::class => \Teamo\Project\Application\Command\Team\RenameTeamMemberHandler::class,

            // Project
            \Teamo\Project\Application\Command\Project\StartNewProjectCommand::class => \Teamo\Project\Application\Command\Project\StartNewProjectHandler::class,
            \Teamo\Project\Application\Command\Project\RenameProjectCommand::class => \Teamo\Project\Application\Command\Project\RenameProjectHandler::class,
            \Teamo\Project\Application\Command\Project\ArchiveProjectCommand::class => \Teamo\Project\Application\Command\Project\ArchiveProjectHandler::class,
            \Teamo\Project\Application\Command\Project\RestoreProjectCommand::class => \Teamo\Project\Application\Command\Project\RestoreProjectHandler::class,

            // Discussion
            \Teamo\Project\Application\Command\Discussion\StartDiscussionCommand::class => \Teamo\Project\Application\Command\Discussion\StartDiscussionHandler::class,
            \Teamo\Project\Application\Command\Discussion\UpdateDiscussionCommand::class => \Teamo\Project\Application\Command\Discussion\UpdateDiscussionHandler::class,
            \Teamo\Project\Application\Command\Discussion\RemoveDiscussionCommand::class => \Teamo\Project\Application\Command\Discussion\RemoveDiscussionHandler::class,
            \Teamo\Project\Application\Command\Discussion\ArchiveDiscussionCommand::class => \Teamo\Project\Application\Command\Discussion\ArchiveDiscussionHandler::class,
            \Teamo\Project\Application\Command\Discussion\RestoreDiscussionCommand::class => \Teamo\Project\Application\Command\Discussion\RestoreDiscussionHandler::class,
            \Teamo\Project\Application\Command\Discussion\RemoveAttachmentOfDiscussionCommand::class => \Teamo\Project\Application\Command\Discussion\RemoveAttachmentOfDiscussionHandler::class,
            \Teamo\Project\Application\Command\Discussion\PostDiscussionCommentCommand::class => \Teamo\Project\Application\Command\Discussion\PostDiscussionCommentHandler::class,
            \Teamo\Project\Application\Command\Discussion\UpdateDiscussionCommentCommand::class => \Teamo\Project\Application\Command\Discussion\UpdateDiscussionCommentHandler::class,
            \Teamo\Project\Application\Command\Discussion\RemoveDiscussionCommentCommand::class => \Teamo\Project\Application\Command\Discussion\RemoveDiscussionCommentHandler::class,
            \Teamo\Project\Application\Command\Discussion\RemoveAttachmentOfDiscussionCommentCommand::class => \Teamo\Project\Application\Command\Discussion\RemoveAttachmentOfDiscussionCommentHandler::class,

            // Event
            \Teamo\Project\Application\Command\Event\ScheduleEventCommand::class => \Teamo\Project\Application\Command\Event\ScheduleEventHandler::class,
            \Teamo\Project\Application\Command\Event\UpdateEventCommand::class => \Teamo\Project\Application\Command\Event\UpdateEventHandler::class,
            \Teamo\Project\Application\Command\Event\RemoveEventCommand::class => \Teamo\Project\Application\Command\Event\RemoveEventHandler::class,
            \Teamo\Project\Application\Command\Event\ArchiveEventCommand::class => \Teamo\Project\Application\Command\Event\ArchiveEventHandler::class,
            \Teamo\Project\Application\Command\Event\RestoreEventCommand::class => \Teamo\Project\Application\Command\Event\RestoreEventHandler::class,
            \Teamo\Project\Application\Command\Event\PostEventCommentCommand::class => \Teamo\Project\Application\Command\Event\PostEventCommentHandler::class,
            \Teamo\Project\Application\Command\Event\UpdateEventCommentCommand::class => \Teamo\Project\Application\Command\Event\UpdateEventCommentHandler::class,
            \Teamo\Project\Application\Command\Event\RemoveEventCommentCommand::class => \Teamo\Project\Application\Command\Event\RemoveEventCommentHandler::class,
            \Teamo\Project\Application\Command\Event\RemoveAttachmentOfEventCommentCommand::class => \Teamo\Project\Application\Command\Event\RemoveAttachmentOfEventCommentHandler::class,
        ];
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /** @var CommandBus $commandBus */
        $commandBus = $this->app->make(CommandBus::class);

        foreach ($this->mappings() as $command => $handler) {
            if (!is_array($handler)) {
                $commandBus->subscribe($command, $handler);
            } else {
                foreach ($handler as $h) {
                    $commandBus->subscribe($command, $h);
                }
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CommandBus::class, SimpleCommandBus::class);
    }
}
