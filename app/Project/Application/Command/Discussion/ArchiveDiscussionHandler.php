<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\ProjectId;

class ArchiveDiscussionHandler extends DiscussionHandler
{
    public function handle(ArchiveDiscussionCommand $command)
    {
        $discussion = $this->discussionRepository->ofId(new DiscussionId($command->discussionId()), new ProjectId($command->projectId()));

        $discussion->archive();
    }
}
