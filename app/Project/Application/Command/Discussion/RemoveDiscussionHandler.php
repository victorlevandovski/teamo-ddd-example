<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\ProjectId;

class RemoveDiscussionHandler extends DiscussionHandler
{
    public function handle(RemoveDiscussionCommand $command)
    {
        $discussion = $this->discussionRepository->ofId(new DiscussionId($command->discussionId()), new ProjectId($command->projectId()));

        $this->discussionRepository->remove($discussion);
    }
}
