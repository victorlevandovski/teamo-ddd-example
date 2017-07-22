<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Application\Command;

use Illuminate\Support\Collection;
use Teamo\Project\Application\Command\Discussion\ArchiveDiscussionCommand;
use Teamo\Project\Application\Command\Discussion\ArchiveDiscussionHandler;
use Teamo\Project\Application\Command\Discussion\PostDiscussionCommentCommand;
use Teamo\Project\Application\Command\Discussion\PostDiscussionCommentHandler;
use Teamo\Project\Application\Command\Discussion\RemoveAttachmentOfDiscussionCommand;
use Teamo\Project\Application\Command\Discussion\RemoveAttachmentOfDiscussionCommentCommand;
use Teamo\Project\Application\Command\Discussion\RemoveAttachmentOfDiscussionCommentHandler;
use Teamo\Project\Application\Command\Discussion\RemoveAttachmentOfDiscussionHandler;
use Teamo\Project\Application\Command\Discussion\RemoveDiscussionCommand;
use Teamo\Project\Application\Command\Discussion\RemoveDiscussionCommentCommand;
use Teamo\Project\Application\Command\Discussion\RemoveDiscussionCommentHandler;
use Teamo\Project\Application\Command\Discussion\RemoveDiscussionHandler;
use Teamo\Project\Application\Command\Discussion\RestoreDiscussionCommand;
use Teamo\Project\Application\Command\Discussion\RestoreDiscussionHandler;
use Teamo\Project\Application\Command\Discussion\StartDiscussionCommand;
use Teamo\Project\Application\Command\Discussion\StartDiscussionHandler;
use Teamo\Project\Application\Command\Discussion\UpdateDiscussionCommand;
use Teamo\Project\Application\Command\Discussion\UpdateDiscussionCommentCommand;
use Teamo\Project\Application\Command\Discussion\UpdateDiscussionCommentHandler;
use Teamo\Project\Application\Command\Discussion\UpdateDiscussionHandler;
use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentManager;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMember;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryDiscussionCommentRepository;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryDiscussionRepository;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryTeamMemberRepository;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryProjectRepository;
use Tests\TestCase;

class DiscussionHandlersTest extends TestCase
{
    /** @var InMemoryProjectRepository */
    private $projectRepository;

    /** @var InMemoryDiscussionRepository */
    private $discussionRepository;

    /** @var  InMemoryDiscussionCommentRepository */
    private $commentRepository;

    /** @var AttachmentManager */
    private $attachmentManager;

    /** @var  Discussion */
    private $discussion;

    /** @var Project */
    private $project;

    public function setUp()
    {
        parent::setUp();

        $this->projectRepository = new InMemoryProjectRepository();
        $this->discussionRepository = new InMemoryDiscussionRepository();
        $this->commentRepository = new InMemoryDiscussionCommentRepository();

        $this->attachmentManager = \Mockery::mock(AttachmentManager::class);
        $this->attachmentManager
            ->shouldReceive('attachmentsFromUploadedFiles')
            ->andReturn(new Collection([new Attachment('a-1', 'image.jpg')]));

        $teamMemberRepository = new InMemoryTeamMemberRepository();
        $owner = new TeamMemberId('t-1');
        $teamMember = new TeamMember($owner, 'John Doe');
        $teamMemberRepository->add($teamMember);

        $this->project = Project::start($teamMember, new ProjectId('p-1'), 'My project');
        $this->projectRepository->add($this->project);

        $this->discussion = $this->project->startDiscussion(
            new DiscussionId('d-1'),
            $owner,
            'My topic',
            'My content',
            new Collection([new Attachment('a-d-1', 'attachment.txt')])
        );
        $this->discussionRepository->add($this->discussion);
    }

    public function testStartDiscussionCommandAddsDiscussionToRepository()
    {
        $command = new StartDiscussionCommand('p-1', 'test-discussion-1', 't-1', 'Topic', 'Content', []);
        $handler = new StartDiscussionHandler($this->discussionRepository, $this->projectRepository, $this->attachmentManager);
        $handler->handle($command);

        $discussion = $this->discussionRepository->ofId(new DiscussionId('test-discussion-1'), new ProjectId('p-1'));

        $this->assertEquals($discussion->author()->id(), $this->project->owner()->id());
        $this->assertEquals('Topic', $discussion->topic());
        $this->assertEquals('Content', $discussion->content());
    }

    public function testUpdateDiscussionCommandUpdatesDiscussion()
    {
        $command = new UpdateDiscussionCommand('p-1', 'd-1', 't-1', 'New topic', 'New content', ['file.tmp' => 'image.jpg']);
        $handler = new UpdateDiscussionHandler($this->discussionRepository, $this->attachmentManager);
        $handler->handle($command);

        $discussion = $this->discussionRepository->ofId(new DiscussionId('d-1'), new ProjectId('p-1'));
        $this->assertEquals('New topic', $discussion->topic());
        $this->assertEquals('New content', $discussion->content());
        $this->assertEquals('image.jpg', $discussion->attachments()->get('a-1')->name());
    }

    public function testDiscussionCommentHandlersDoTheirJob()
    {
        $command = new PostDiscussionCommentCommand('p-1', 'd-1', 'c-1', 't-1', 'Comment', []);
        $handler = new PostDiscussionCommentHandler($this->discussionRepository, $this->commentRepository, $this->attachmentManager);
        $handler->handle($command);
        $comment = $this->commentRepository->ofId(new CommentId('c-1'), new DiscussionId('d-1'));
        $this->assertEquals('t-1', $comment->author()->id());
        $this->assertEquals('d-1', $comment->discussionId()->id());
        $this->assertEquals('Comment', $comment->content());
        $this->assertEquals('image.jpg', $comment->attachments()->first()->name());

        $command = new UpdateDiscussionCommentCommand('p-1', 'd-1', 'c-1', 't-1', 'New comment');
        $handler = new UpdateDiscussionCommentHandler($this->commentRepository);
        $handler->handle($command);
        $comment = $this->commentRepository->ofId(new CommentId('c-1'), new DiscussionId('d-1'));
        $this->assertEquals('New comment', $comment->content());

        $command = new RemoveAttachmentOfDiscussionCommentCommand('p-1', 'd-1', 'c-1', 'a-1', 't-1');
        $handler = new RemoveAttachmentOfDiscussionCommentHandler($this->commentRepository);
        $handler->handle($command);
        $comment = $this->commentRepository->ofId(new CommentId('c-1'), new DiscussionId('d-1'));
        $this->assertTrue($comment->attachments()->isEmpty());

        $command = new RemoveDiscussionCommentCommand('p-1', 'd-1', 'c-1', 't-1');
        $handler = new RemoveDiscussionCommentHandler($this->commentRepository);
        $handler->handle($command);
        $this->expectException(\InvalidArgumentException::class);
        $this->commentRepository->ofId(new CommentId('c-1'), new DiscussionId('d-1'));
    }

    public function testRemoveAttachmentOfDiscussionHandlerRemovesAttachment()
    {
        $command = new RemoveAttachmentOfDiscussionCommand('p-1', 'd-1', 'a-d-1', 't-1');
        $handler = new RemoveAttachmentOfDiscussionHandler($this->discussionRepository);
        $handler->handle($command);

        $discussion = $this->discussionRepository->ofId(new DiscussionId('d-1'), new ProjectId('p-1'));
        $this->assertTrue($discussion->attachments()->isEmpty());
    }

    public function testArchiveDiscussionHandlerArchivesDiscussion()
    {
        $command = new ArchiveDiscussionCommand('p-1', 'd-1', 't-1');
        $handler = new ArchiveDiscussionHandler($this->discussionRepository);
        $handler->handle($command);

        $discussion = $this->discussionRepository->ofId(new DiscussionId('d-1'), new ProjectId('p-1'));
        $this->assertTrue($discussion->isArchived());
    }

    public function testRestoreDiscussionHandlerRestoresDiscussion()
    {
        $command = new RestoreDiscussionCommand('p-1', 'd-1', 't-1');
        $handler = new RestoreDiscussionHandler($this->discussionRepository);
        $handler->handle($command);

        $discussion = $this->discussionRepository->ofId(new DiscussionId('d-1'), new ProjectId('p-1'));
        $this->assertFalse($discussion->isArchived());
    }

    public function testRemoveDiscussionHandlerRemovesDiscussion()
    {
        $command = new RemoveDiscussionCommand('p-1', 'd-1', 't-1');
        $handler = new RemoveDiscussionHandler($this->discussionRepository);
        $handler->handle($command);

        $this->expectException(\InvalidArgumentException::class);
        $this->discussionRepository->ofId(new DiscussionId('d-1'), new ProjectId('p-1'));
    }
}
