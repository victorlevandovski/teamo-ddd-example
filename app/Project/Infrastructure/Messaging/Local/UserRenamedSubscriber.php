<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Messaging\Local;

use Teamo\Common\Application\CommandBus;
use Teamo\Common\Domain\DomainEventSubscriber;
use Teamo\Project\Application\Command\Team\RenameTeamMemberCommand;

class UserRenamedSubscriber implements DomainEventSubscriber
{
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function isSubscribedTo(string $eventType): bool
    {
        return $eventType == 'Teamo\User\Domain\Model\User\UserRenamed';
    }

    /** @param \Teamo\User\Domain\Model\User\UserRenamed $event */
    public function handle($event)
    {
        $command = new RenameTeamMemberCommand($event->userId()->id(), $event->name());
        $this->commandBus->handle($command);
    }
}
