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

            // TeamMember
            \Teamo\Project\Application\Command\Team\RegisterTeamMemberCommand::class => \Teamo\Project\Application\Command\Team\RegisterTeamMemberHandler::class,
            \Teamo\Project\Application\Command\Team\RenameTeamMemberCommand::class => \Teamo\Project\Application\Command\Team\RenameTeamMemberHandler::class,
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
