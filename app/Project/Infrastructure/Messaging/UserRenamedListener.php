<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Messaging;

use Teamo\Common\Application\CommandBus;
use Teamo\Common\Domain\DomainEventSubscriber;
use Teamo\Project\Application\Command\Team\RenameTeamMemberCommand;
use Teamo\User\Domain\Model\User\UserRenamed;

class UserRenamedListener implements DomainEventSubscriber
{
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function isSubscribedTo(string $eventType): bool
    {
        return $eventType == UserRenamed::class;
    }

    /** @param UserRenamed $event */
    public function handle($event)
    {
        $command = new RenameTeamMemberCommand($event->userId()->id(), $event->name());
        $this->commandBus->handle($command);
    }
}
