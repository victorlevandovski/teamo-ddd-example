<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Event;

use Teamo\Project\Domain\Model\Project\Attachment\AttachmentManager;
use Teamo\Project\Domain\Model\Project\Attachment\UploadedFile;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Event\EventCommentRepository;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\Event\EventRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class PostEventCommentHandler extends EventHandler
{
    private $commentRepository;
    private $attachmentManager;

    public function __construct(
        EventRepository $eventRepository,
        EventCommentRepository $commentRepository,
        AttachmentManager $attachmentManager
    ) {
        parent::__construct($eventRepository);

        $this->commentRepository = $commentRepository;
        $this->attachmentManager = $attachmentManager;
    }

    public function handle(PostEventCommentCommand $command)
    {
        $uploadedFiles = [];
        foreach ($command->attachments() as $file => $name) {
            $uploadedFiles[] = new UploadedFile($file, $name);
        }

        $event = $this->eventRepository->ofId(new EventId($command->eventId()), new ProjectId($command->projectId()));

        $comment = $event->comment(
            new CommentId($command->commentId()),
            new TeamMemberId($command->author()),
            $command->content(),
            $this->attachmentManager->attachmentsFromUploadedFiles($uploadedFiles)
        );

        $this->commentRepository->add($comment);
    }
}
