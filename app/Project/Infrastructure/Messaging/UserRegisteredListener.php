<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Messaging;

use Teamo\Common\Application\CommandBus;
use Teamo\Common\Domain\DomainEventSubscriber;
use Teamo\Project\Application\Command\Team\RegisterTeamMemberCommand;
use Teamo\User\Domain\Model\User\UserRegistered;

class UserRegisteredListener implements DomainEventSubscriber
{
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function isSubscribedTo(string $eventType): bool
    {
        return $eventType == UserRegistered::class;
    }

    /** @param UserRegistered $event */
    public function handle($event)
    {
        $command = new RegisterTeamMemberCommand($event->userId()->id(), $event->name());
        $this->commandBus->handle($command);
    }
}
