<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionCommentRepository;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class RemoveAttachmentOfDiscussionCommentHandler
{
    private $commentRepository;

    public function __construct(DiscussionCommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function handle(RemoveAttachmentOfDiscussionCommentCommand $command)
    {
        $comment = $this->commentRepository->ofId(new CommentId($command->commentId()), new DiscussionId($command->discussionId()));

        $comment->removeAttachment($command->attachmentId(), new TeamMemberId($command->author()));
    }
}
