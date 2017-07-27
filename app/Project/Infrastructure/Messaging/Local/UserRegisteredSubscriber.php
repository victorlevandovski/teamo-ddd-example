<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Messaging\Local;

use Teamo\Common\Application\CommandBus;
use Teamo\Common\Domain\DomainEventSubscriber;
use Teamo\Project\Application\Command\Team\RegisterTeamMemberCommand;

class UserRegisteredSubscriber implements DomainEventSubscriber
{
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function isSubscribedTo(string $eventType): bool
    {
        return $eventType == 'Teamo\User\Domain\Model\User\UserRegistered';
    }

    /** @param \Teamo\User\Domain\Model\User\UserRegistered $event */
    public function handle($event)
    {
        $command = new RegisterTeamMemberCommand($event->userId()->id(), $event->name());
        $this->commandBus->handle($command);
    }
}
