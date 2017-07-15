<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Subscriber;

use Teamo\Common\Domain\DomainEventSubscriber;
use Teamo\Project\Domain\Model\Team\TeamMember;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Teamo\Project\Domain\Model\Team\TeamMemberRepository;
use Teamo\User\Domain\Model\User\UserRegistered;

class UserRegisteredSubscriber implements DomainEventSubscriber
{
    private $teamMemberRepository;

    public function __construct(TeamMemberRepository $teamMemberRepository)
    {
        $this->teamMemberRepository = $teamMemberRepository;
    }

    public function isSubscribedTo(string $eventType): bool
    {
        return $eventType == UserRegistered::class;
    }

    /** @param UserRegistered $event */
    public function handle($event)
    {
        $this->teamMemberRepository->add(new TeamMember(
            new TeamMemberId($event->userId()->id()),
            $event->name()
        ));
    }
}
