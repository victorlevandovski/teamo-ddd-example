<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

use Teamo\Project\Domain\Model\Project\Attachment\AttachmentManager;
use Teamo\Project\Domain\Model\Project\Attachment\UploadedFile;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class StartDiscussionHandler extends DiscussionHandler
{
    private $projectRepository;
    private $attachmentManager;

    public function __construct(
        DiscussionRepository $discussionRepository,
        ProjectRepository $projectRepository,
        AttachmentManager $attachmentManager
    ) {
        parent::__construct($discussionRepository);

        $this->projectRepository = $projectRepository;
        $this->attachmentManager = $attachmentManager;
    }

    public function handle(StartDiscussionCommand $command)
    {
        $uploadedFiles = [];
        foreach ($command->attachments() as $file => $name) {
            $uploadedFiles[] = new UploadedFile($file, $name);
        }

        $teamMemberId = new TeamMemberId($command->author());

        $project = $this->projectRepository->ofId(new ProjectId($command->projectId()), $teamMemberId);

        $discussion = $project->startDiscussion(
            new DiscussionId($command->discussionId()),
            $teamMemberId,
            $command->topic(),
            $command->content(),
            $this->attachmentManager->attachmentsFromUploadedFiles($uploadedFiles)
        );

        $this->discussionRepository->add($discussion);
    }
}
