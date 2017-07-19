<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

use Teamo\Common\Application\Exception\NotAuthorizedException;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentId;
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
        $author = new TeamMemberId($command->author());

        $comment = $this->commentRepository->ofId(new CommentId($command->commentId()), new DiscussionId($command->discussionId()));

        if ($comment->author()->equals($author)) {
            $comment->removeAttachment(new AttachmentId($command->attachmentId()));
        } else {
            throw new NotAuthorizedException();
        }
    }
}
