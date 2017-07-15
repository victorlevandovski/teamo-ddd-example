<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Subscriber;

use Teamo\Common\Domain\DomainEventSubscriber;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Teamo\Project\Domain\Model\Team\TeamMemberRepository;
use Teamo\User\Domain\Model\User\UserRegistered;
use Teamo\User\Domain\Model\User\UserRenamed;

class UserRenamedSubscriber implements DomainEventSubscriber
{
    private $teamMemberRepository;

    public function __construct(TeamMemberRepository $teamMemberRepository)
    {
        $this->teamMemberRepository = $teamMemberRepository;
    }

    public function isSubscribedTo(string $eventType): bool
    {
        return $eventType == UserRenamed::class;
    }

    /** @param UserRegistered $event */
    public function handle($event)
    {
        $teamMember = $this->teamMemberRepository->ofId(new TeamMemberId($event->userId()->id()));
        $teamMember->rename($event->name());
    }
}
