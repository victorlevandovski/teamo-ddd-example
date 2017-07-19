<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\ProjectId;

class RestoreDiscussionHandler extends DiscussionHandler
{
    public function handle(RestoreDiscussionCommand $command)
    {
        $discussion = $this->discussionRepository->ofId(new DiscussionId($command->discussionId()), new ProjectId($command->projectId()));

        $discussion->restore();
    }
}
