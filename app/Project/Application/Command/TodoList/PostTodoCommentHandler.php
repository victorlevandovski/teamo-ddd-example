<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\TodoList;

use Teamo\Project\Domain\Model\Project\Attachment\AttachmentManager;
use Teamo\Project\Domain\Model\Project\Attachment\UploadedFile;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoCommentRepository;
use Teamo\Project\Domain\Model\Project\TodoList\TodoId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class PostTodoCommentHandler extends TodoListHandler
{
    private $commentRepository;
    private $attachmentManager;

    public function __construct(
        TodoListRepository $todoListRepository,
        TodoCommentRepository $commentRepository,
        AttachmentManager $attachmentManager
    ) {
        parent::__construct($todoListRepository);

        $this->commentRepository = $commentRepository;
        $this->attachmentManager = $attachmentManager;
    }

    public function handle(PostTodoCommentCommand $command)
    {
        $uploadedFiles = [];
        foreach ($command->attachments() as $file => $name) {
            $uploadedFiles[] = new UploadedFile($file, $name);
        }

        $todoList = $this->todoListRepository->ofId(new TodoListId($command->todoListId()), new ProjectId($command->projectId()));

        $comment = $todoList->todo(new TodoId($command->todoId()))->comment(
            new CommentId($command->commentId()),
            new TeamMemberId($command->author()),
            $command->content(),
            $this->attachmentManager->attachmentsFromUploadedFiles($uploadedFiles)
        );

        $this->commentRepository->add($comment);
    }
}
