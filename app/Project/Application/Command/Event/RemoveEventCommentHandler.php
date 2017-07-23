<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Event;

use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Event\EventCommentRepository;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class RemoveEventCommentHandler
{
    private $commentRepository;

    public function __construct(EventCommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function handle(RemoveEventCommentCommand $command)
    {
        $comment = $this->commentRepository->ofId(new CommentId($command->commentId()), new EventId($command->eventId()));

        $comment->assertCanUpdate(new TeamMemberId($command->author()));
        $this->commentRepository->remove($comment);
    }
}
