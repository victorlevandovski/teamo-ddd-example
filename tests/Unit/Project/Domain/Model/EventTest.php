<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Domain\Model\Project;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentId;
use Teamo\Project\Domain\Model\Project\Event\Event;
use Teamo\Project\Domain\Model\Project\Event\EventComment;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Collaborator\Creator;
use Tests\TestCase;

class EventTest extends TestCase
{
    /**
     * @var Event
     */
    private $event;

    public function setUp()
    {
        $this->event = new Event(
            new ProjectId('id-1'),
            new EventId('id-1'),
            new Creator('id-1', 'John Doe'),
            'My Event',
            'Event Details',
            '2020-01-01 00:00:00'
        );
    }

    public function testConstructedEventIsValid()
    {
        $projectId = ProjectId::generate();
        $eventId = EventId::generate();
        $creator = new Creator('author-1', 'John Doe');
        $attachments = new Collection(new Attachment(AttachmentId::generate(), 'Attachment.txt'));

        $event = new Event($projectId, $eventId, $creator, 'Name', 'Details', '2020-01-01 00:00:00', $attachments);

        $this->assertSame($projectId, $event->projectId());
        $this->assertSame($eventId, $event->eventId());
        $this->assertSame($creator, $event->creator());
        $this->assertEquals('Name', $event->name());
        $this->assertEquals('Details', $event->details());
        $this->assertEquals('2020-01-01 00:00:00', $event->startsAt());
        $this->assertSame($attachments, $event->attachments());
    }

    public function testEventCanBeCommented()
    {
        $author = new Author('id-1', 'John Doe');

        $comment = $this->event->comment($author, 'Comment content');

        $this->assertInstanceOf(EventComment::class, $comment);
    }

    public function testEventCanBeUpdated()
    {
        $this->assertEquals('My Event', $this->event->name());
        $this->assertEquals('Event Details', $this->event->details());

        $this->event->update('New Event', 'New Details');
        $this->assertEquals('New Event', $this->event->name());
        $this->assertEquals('New Details', $this->event->details());
    }

    public function testEventCanBeArchivedAndRestored()
    {
        $this->assertFalse($this->event->isArchived());

        $this->event->archive();
        $this->assertTrue($this->event->isArchived());

        $this->event->restore();
        $this->assertFalse($this->event->isArchived());
    }
}
