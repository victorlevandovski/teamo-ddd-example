<?php

namespace Teamo\Common\Provider;

use Illuminate\Support\ServiceProvider;
use Teamo\Common\Application\CommandBus;
use Teamo\Common\Application\SimpleCommandBus;
use Teamo\User\Application\Command\User\ChangeUserEmailCommand;
use Teamo\User\Application\Command\User\ChangeUserEmailHandler;
use Teamo\User\Application\Command\User\ChangeUserPasswordCommand;
use Teamo\User\Application\Command\User\ChangeUserPasswordHandler;
use Teamo\User\Application\Command\User\RegisterUserCommand;
use Teamo\User\Application\Command\User\RegisterUserHandler;
use Teamo\User\Application\Command\User\RemoveUserAvatarCommand;
use Teamo\User\Application\Command\User\RemoveUserAvatarHandler;
use Teamo\User\Application\Command\User\UpdateUserAvatarCommand;
use Teamo\User\Application\Command\User\UpdateUserAvatarHandler;
use Teamo\User\Application\Command\User\UpdateUserNotificationSettingsCommand;
use Teamo\User\Application\Command\User\UpdateUserNotificationSettingsHandler;
use Teamo\User\Application\Command\User\UpdateUserProfileCommand;
use Teamo\User\Application\Command\User\UpdateUserProfileHandler;

class CommandBusServiceProvider extends ServiceProvider
{
    private function mappings(): array
    {
        return [
            RegisterUserCommand::class => RegisterUserHandler::class,
            UpdateUserProfileCommand::class => UpdateUserProfileHandler::class,
            UpdateUserNotificationSettingsCommand::class => UpdateUserNotificationSettingsHandler::class,
            ChangeUserEmailCommand::class => ChangeUserEmailHandler::class,
            ChangeUserPasswordCommand::class => ChangeUserPasswordHandler::class,
            UpdateUserAvatarCommand::class => UpdateUserAvatarHandler::class,
            RemoveUserAvatarCommand::class => RemoveUserAvatarHandler::class,
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
