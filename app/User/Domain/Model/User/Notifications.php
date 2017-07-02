<?php
declare(strict_types=1);

namespace Teamo\User\Domain\Model\User;

use Teamo\Common\Domain\ValueObject;

class Notifications extends ValueObject
{
    private $discussionStarted;
    private $discussionCommented;
    private $todoListCreated;
    private $todoCommented;
    private $todoAssignedToMe;
    private $eventAdded;
    private $eventCommented;

    public function __construct(
        bool $whenDiscussionStarted,
        bool $whenDiscussionCommented,
        bool $whenTodoListCreated,
        bool $whenTodoCommented,
        bool $whenTodoAssignedToMe,
        bool $whenEventAdded,
        bool $whenEventCommented
    ) {
        $this->discussionStarted = $whenDiscussionStarted;
        $this->discussionCommented = $whenDiscussionCommented;
        $this->todoListCreated = $whenTodoListCreated;
        $this->todoCommented = $whenTodoCommented;
        $this->todoAssignedToMe = $whenTodoAssignedToMe;
        $this->eventAdded  = $whenEventAdded;
        $this->eventCommented = $whenEventCommented;
    }

    public function whenDiscussionStarted(): bool
    {
        return $this->discussionStarted;
    }

    public function whenDiscussionCommented(): bool
    {
        return $this->discussionCommented;
    }

    public function whenTodoListCreated(): bool
    {
        return $this->todoListCreated;
    }

    public function whenTodoCommented(): bool
    {
        return $this->todoCommented;
    }

    public function whenTodoAssignedToMe(): bool
    {
        return $this->todoAssignedToMe;
    }

    public function whenEventAdded(): bool
    {
        return $this->eventAdded;
    }

    public function whenEventCommented(): bool
    {
        return $this->eventCommented;
    }
}
