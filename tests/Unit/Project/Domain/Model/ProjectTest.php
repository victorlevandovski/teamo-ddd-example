<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Domain\Model\Project;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentId;
use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\Event\Event;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoList;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    /**
     * @var Project
     */
    private $project;

    public function setUp()
    {
        $this->project = new Project(new TeamMemberId('id-1'), new ProjectId('id-1'), 'My Project', false);
    }

    public function testProjectCanBeStarted()
    {
        $ownerId = new TeamMemberId('owner');
        $projectId = new ProjectId('project');

        $project = Project::start($ownerId, $projectId, 'My Project');

        $this->assertSame($ownerId, $project->ownerId());
        $this->assertSame($projectId, $project->projectId());
        $this->assertEquals('My Project', $project->name());
        $this->assertFalse($project->isArchived());
    }

    public function testProjectCanStartDiscussion()
    {
        $authorId = new TeamMemberId('id-1');
        $attachments = new Collection(new Attachment(new AttachmentId('1'), 'attachment.txt'));

        $discussion = $this->project->startDiscussion(new DiscussionId('1'), $authorId, 'New Discussion', 'Discussion content', $attachments);

        $this->assertInstanceOf(Discussion::class, $discussion);
    }

    public function testProjectCanCreateTodoList()
    {
        $creatorId = new TeamMemberId('id-1');
        $todoList = $this->project->createTodoList(new TodoListId('1'), $creatorId, 'New Todo List');

        $this->assertInstanceOf(TodoList::class, $todoList);
    }

    public function testProjectCanScheduleEvent()
    {
        $creatorId = new TeamMemberId('id-1');
        $attachments = new Collection(new Attachment(new AttachmentId('1'), 'attachment.txt'));

        $event = $this->project->scheduleEvent(new EventId('1'), $creatorId, 'My Event', 'Event details', new Carbon(), $attachments);

        $this->assertInstanceOf(Event::class, $event);
    }

    public function testProjectCanBeRenamed()
    {
        $this->assertEquals('My Project', $this->project->name());

        $this->project->rename('Project');
        $this->assertEquals('Project', $this->project->name());
    }

    public function testProjectCanBeArchivedAndRestored()
    {
        $this->assertFalse($this->project->isArchived());

        $this->project->archive();
        $this->assertTrue($this->project->isArchived());

        $this->project->restore();
        $this->assertFalse($this->project->isArchived());
    }
}
