<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

use Teamo\Project\Domain\Model\Project\Attachment\AttachmentId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\ProjectId;

class RemoveAttachmentOfDiscussionHandler extends DiscussionHandler
{
    public function handle(RemoveAttachmentOfDiscussionCommand $command)
    {
        $discussion = $this->discussionRepository->ofId(new DiscussionId($command->discussionId()), new ProjectId($command->projectId()));

        $discussion->removeAttachment(new AttachmentId($command->attachmentId()));
    }
}
