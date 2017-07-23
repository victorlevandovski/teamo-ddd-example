<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Event;

use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Event\EventCommentRepository;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class RemoveAttachmentOfEventCommentHandler
{
    private $commentRepository;

    public function __construct(EventCommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function handle(RemoveAttachmentOfEventCommentCommand $command)
    {
        $comment = $this->commentRepository->ofId(new CommentId($command->commentId()), new EventId($command->eventId()));

        $comment->removeAttachment($command->attachmentId(), new TeamMemberId($command->author()));
    }
}
