<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Event;

use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Event\EventCommentRepository;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class UpdateEventCommentHandler
{
    private $commentRepository;

    public function __construct(EventCommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function handle(UpdateEventCommentCommand $command)
    {
        $comment = $this->commentRepository->ofId(new CommentId($command->commentId()), new EventId($command->eventId()));

        $comment->update($command->content(), new TeamMemberId($command->author()));
    }
}
