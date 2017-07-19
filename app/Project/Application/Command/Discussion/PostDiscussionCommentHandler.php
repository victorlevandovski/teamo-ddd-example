<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Discussion;

use Teamo\Project\Domain\Model\Project\Attachment\AttachmentManager;
use Teamo\Project\Domain\Model\Project\Attachment\UploadedFile;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionCommentRepository;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class PostDiscussionCommentHandler extends DiscussionHandler
{
    private $commentRepository;
    private $attachmentManager;

    public function __construct(
        DiscussionRepository $discussionRepository,
        DiscussionCommentRepository $commentRepository,
        AttachmentManager $attachmentManager
    ) {
        parent::__construct($discussionRepository);

        $this->commentRepository = $commentRepository;
        $this->attachmentManager = $attachmentManager;
    }

    public function handle(PostDiscussionCommentCommand $command)
    {
        $uploadedFiles = [];
        foreach ($command->attachments() as $file => $name) {
            $uploadedFiles[] = new UploadedFile($file, $name);
        }

        $discussion = $this->discussionRepository->ofId(new DiscussionId($command->discussionId()), new ProjectId($command->projectId()));

        $comment = $discussion->comment(
            new CommentId($command->commentId()),
            new TeamMemberId($command->author()),
            $command->content(),
            $this->attachmentManager->attachmentsFromUploadedFiles($uploadedFiles)
        );

        $this->commentRepository->add($comment);
    }
}
