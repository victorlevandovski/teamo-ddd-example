<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Application\Command;

use Illuminate\Support\Collection;
use Teamo\Project\Application\Command\Event\ArchiveEventCommand;
use Teamo\Project\Application\Command\Event\ArchiveEventHandler;
use Teamo\Project\Application\Command\Event\PostEventCommentCommand;
use Teamo\Project\Application\Command\Event\PostEventCommentHandler;
use Teamo\Project\Application\Command\Event\RemoveAttachmentOfEventCommentCommand;
use Teamo\Project\Application\Command\Event\RemoveAttachmentOfEventCommentHandler;
use Teamo\Project\Application\Command\Event\RemoveEventCommand;
use Teamo\Project\Application\Command\Event\RemoveEventCommentCommand;
use Teamo\Project\Application\Command\Event\RemoveEventCommentHandler;
use Teamo\Project\Application\Command\Event\RemoveEventHandler;
use Teamo\Project\Application\Command\Event\RestoreEventCommand;
use Teamo\Project\Application\Command\Event\RestoreEventHandler;
use Teamo\Project\Application\Command\Event\ScheduleEventCommand;
use Teamo\Project\Application\Command\Event\ScheduleEventHandler;
use Teamo\Project\Application\Command\Event\UpdateEventCommand;
use Teamo\Project\Application\Command\Event\UpdateEventCommentCommand;
use Teamo\Project\Application\Command\Event\UpdateEventCommentHandler;
use Teamo\Project\Application\Command\Event\UpdateEventHandler;
use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentManager;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Event\Event;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMember;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryEventCommentRepository;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryEventRepository;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryTeamMemberRepository;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryProjectRepository;
use Tests\TestCase;

class EventHandlersTest extends TestCase
{
    /** @var InMemoryProjectRepository */
    private $projectRepository;

    /** @var InMemoryEventRepository */
    private $eventRepository;

    /** @var  InMemoryEventCommentRepository */
    private $commentRepository;

    /** @var AttachmentManager */
    private $attachmentManager;

    /** @var  Event */
    private $event;

    /** @var Project */
    private $project;

    public function setUp()
    {
        parent::setUp();

        $this->projectRepository = new InMemoryProjectRepository();
        $this->eventRepository = new InMemoryEventRepository();
        $this->commentRepository = new InMemoryEventCommentRepository();

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

        $this->event = $this->project->scheduleEvent(
            new EventId('d-1'),
            $owner,
            'My name',
            'My details',
            new \DateTimeImmutable()
        );
        $this->eventRepository->add($this->event);
    }

    public function testScheduleEventCommandAddsEventToRepository()
    {
        $command = new ScheduleEventCommand('p-1', 'test-event-1', 't-1', 'Name', 'Details', '2020-01-01 12:00:00');
        $handler = new ScheduleEventHandler($this->eventRepository, $this->projectRepository);
        $handler->handle($command);

        $event = $this->eventRepository->ofId(new EventId('test-event-1'), new ProjectId('p-1'));

        $this->assertEquals($event->creator()->id(), $this->project->owner()->id());
        $this->assertEquals('Name', $event->name());
        $this->assertEquals('Details', $event->details());
        $this->assertEquals('2020-01-01', $event->occursOn()->format('Y-m-d'));
    }

    public function testUpdateEventCommandUpdatesEvent()
    {
        $command = new UpdateEventCommand('p-1', 'd-1', 't-1', 'New name', 'New details', '2020-02-02 12:00:00');
        $handler = new UpdateEventHandler($this->eventRepository);
        $handler->handle($command);

        $event = $this->eventRepository->ofId(new EventId('d-1'), new ProjectId('p-1'));
        $this->assertEquals('New name', $event->name());
        $this->assertEquals('New details', $event->details());
        $this->assertEquals('2020-02-02', $event->occursOn()->format('Y-m-d'));
    }

    public function testEventCommentHandlersDoTheirJob()
    {
        $command = new PostEventCommentCommand('p-1', 'd-1', 'c-1', 't-1', 'Comment', []);
        $handler = new PostEventCommentHandler($this->eventRepository, $this->commentRepository, $this->attachmentManager);
        $handler->handle($command);
        $comment = $this->commentRepository->ofId(new CommentId('c-1'), new EventId('d-1'));
        $this->assertEquals('t-1', $comment->author()->id());
        $this->assertEquals('d-1', $comment->eventId()->id());
        $this->assertEquals('Comment', $comment->content());
        $this->assertEquals('image.jpg', $comment->attachments()->first()->name());

        $command = new UpdateEventCommentCommand('p-1', 'd-1', 'c-1', 't-1', 'New comment');
        $handler = new UpdateEventCommentHandler($this->commentRepository);
        $handler->handle($command);
        $comment = $this->commentRepository->ofId(new CommentId('c-1'), new EventId('d-1'));
        $this->assertEquals('New comment', $comment->content());

        $command = new RemoveAttachmentOfEventCommentCommand('p-1', 'd-1', 'c-1', 'a-1', 't-1');
        $handler = new RemoveAttachmentOfEventCommentHandler($this->commentRepository);
        $handler->handle($command);
        $comment = $this->commentRepository->ofId(new CommentId('c-1'), new EventId('d-1'));
        $this->assertTrue($comment->attachments()->isEmpty());

        $command = new RemoveEventCommentCommand('p-1', 'd-1', 'c-1', 't-1');
        $handler = new RemoveEventCommentHandler($this->commentRepository);
        $handler->handle($command);
        $this->expectException(\InvalidArgumentException::class);
        $this->commentRepository->ofId(new CommentId('c-1'), new EventId('d-1'));
    }

    public function testArchiveEventHandlerArchivesEvent()
    {
        $command = new ArchiveEventCommand('p-1', 'd-1', 't-1');
        $handler = new ArchiveEventHandler($this->eventRepository);
        $handler->handle($command);

        $event = $this->eventRepository->ofId(new EventId('d-1'), new ProjectId('p-1'));
        $this->assertTrue($event->isArchived());
    }

    public function testRestoreEventHandlerRestoresEvent()
    {
        $command = new RestoreEventCommand('p-1', 'd-1', 't-1');
        $handler = new RestoreEventHandler($this->eventRepository);
        $handler->handle($command);

        $event = $this->eventRepository->ofId(new EventId('d-1'), new ProjectId('p-1'));
        $this->assertFalse($event->isArchived());
    }

    public function testRemoveEventHandlerRemovesEvent()
    {
        $command = new RemoveEventCommand('p-1', 'd-1', 't-1');
        $handler = new RemoveEventHandler($this->eventRepository);
        $handler->handle($command);

        $this->expectException(\InvalidArgumentException::class);
        $this->eventRepository->ofId(new EventId('d-1'), new ProjectId('p-1'));
    }
}
