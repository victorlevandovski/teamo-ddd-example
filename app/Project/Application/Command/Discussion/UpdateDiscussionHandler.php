<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

use Teamo\Project\Domain\Model\Project\Attachment\AttachmentManager;
use Teamo\Project\Domain\Model\Project\Attachment\UploadedFile;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;

class UpdateDiscussionHandler extends DiscussionHandler
{
    private $attachmentManager;

    public function __construct(
        DiscussionRepository $discussionRepository,
        AttachmentManager $attachmentManager
    ) {
        parent::__construct($discussionRepository);

        $this->attachmentManager = $attachmentManager;
    }

    public function handle(UpdateDiscussionCommand $command)
    {
        $uploadedFiles = [];
        foreach ($command->attachments() as $file => $name) {
            $uploadedFiles[] = new UploadedFile($file, $name);
        }

        $discussion = $this->discussionRepository->ofId(new DiscussionId($command->discussionId()), new ProjectId($command->projectId()));

        $discussion->update($command->topic(), $command->content());

        if ($uploadedFiles) {
            foreach ($this->attachmentManager->attachmentsFromUploadedFiles($uploadedFiles) as $attachment) {
                $discussion->attach($attachment);
            }
        }
    }
}
