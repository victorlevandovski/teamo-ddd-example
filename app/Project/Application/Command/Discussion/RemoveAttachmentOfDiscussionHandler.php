<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class RemoveAttachmentOfDiscussionHandler extends DiscussionHandler
{
    public function handle(RemoveAttachmentOfDiscussionCommand $command)
    {
        $discussion = $this->discussionRepository->ofId(new DiscussionId($command->discussionId()), new ProjectId($command->projectId()));

        $discussion->removeAttachment($command->attachmentId(), new TeamMemberId($command->teamMemberId()));
    }
}
