<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Domain\Model\Project;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\Event\Event;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoList;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Team\TeamMember;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    /** @var Project */
    private $project;

    public function setUp()
    {
        $this->project = new Project(new TeamMemberId('id-1'), new ProjectId('id-1'), 'My Project', false);
    }

    public function testProjectCanBeStarted()
    {
        $teamMemberId = new TeamMemberId('owner-1');
        $teamMember = new TeamMember($teamMemberId, 'John Doe');
        $projectId = new ProjectId('project');

        $project = Project::start($teamMember, $projectId, 'My Project');

        $this->assertSame($teamMemberId, $project->owner());
        $this->assertSame($projectId, $project->projectId());
        $this->assertEquals('My Project', $project->name());
        $this->assertFalse($project->isArchived());
    }

    public function testProjectCanAddTeamMember()
    {
        $teamMember = new TeamMember(new TeamMemberId('tm-1'), 'John Doe');
        $this->project->addTeamMember($teamMember, $this->project->owner());

        $this->assertSame($teamMember, $this->project->teamMembers()[0]);

        $this->expectException(\InvalidArgumentException::class);
        $anotherTeamMember = new TeamMember(new TeamMemberId('tm-1'), 'Another Team Member');
        $this->project->addTeamMember($anotherTeamMember, new TeamMemberId('wrong-owner-id'));
    }

    public function testProjectCanStartDiscussion()
    {
        $author = new TeamMemberId('id-1');
        $discussionId = new DiscussionId('1');
        $attachments = new Collection([new Attachment('1', 'attachment.txt')]);

        $discussion = $this->project->startDiscussion($discussionId, $author, 'New Discussion', 'Discussion content', $attachments);

        $this->assertInstanceOf(Discussion::class, $discussion);
        $this->assertSame($this->project->projectId(), $discussion->projectId());
        $this->assertSame($discussionId, $discussion->discussionId());
        $this->assertSame($author, $discussion->author());
        $this->assertEquals('New Discussion', $discussion->topic());
        $this->assertEquals('Discussion content', $discussion->content());
        $this->assertCount(1, $discussion->attachments());
    }

    public function testProjectCanCreateTodoList()
    {
        $creator = new TeamMemberId('id-1');
        $todoListId = new TodoListId('1');
        $todoList = $this->project->createTodoList($todoListId, $creator, 'New Todo List');

        $this->assertInstanceOf(TodoList::class, $todoList);
        $this->assertSame($this->project->projectId(), $todoList->projectId());
        $this->assertSame($todoListId, $todoList->todoListId());
        $this->assertSame($creator, $todoList->creator());
        $this->assertEquals('New Todo List', $todoList->name());
    }

    public function testProjectCanScheduleEvent()
    {
        $creator = new TeamMemberId('id-1');
        $eventId = new EventId('1');
        $occursOn = new \DateTimeImmutable();

        $event = $this->project->scheduleEvent($eventId, $creator, 'My Event', 'Event details', $occursOn);

        $this->assertInstanceOf(Event::class, $event);
        $this->assertSame($this->project->projectId(), $event->projectId());
        $this->assertSame($eventId, $event->eventId());
        $this->assertSame($creator, $event->creator());
        $this->assertEquals('My Event', $event->name());
        $this->assertEquals('Event details', $event->details());
        $this->assertSame($occursOn, $event->occursOn());
    }

    public function testProjectCanBeRenamed()
    {
        $this->assertEquals('My Project', $this->project->name());

        $this->project->rename('Project', $this->project->owner());
        $this->assertEquals('Project', $this->project->name());

        $this->expectException(\InvalidArgumentException::class);
        $this->project->rename('Project', new TeamMemberId('wrong-owner-id'));
    }

    public function testProjectCanBeArchived()
    {
        $this->assertFalse($this->project->isArchived());

        $this->project->archive($this->project->owner());
        $this->assertTrue($this->project->isArchived());

        $this->expectException(\InvalidArgumentException::class);
        $this->project->archive(new TeamMemberId('wrong-owner-id'));
    }

    public function testProjectCanBeRestored()
    {
        $this->assertFalse($this->project->isArchived());

        $this->project->restore($this->project->owner());
        $this->assertFalse($this->project->isArchived());

        $this->expectException(\InvalidArgumentException::class);
        $this->project->restore(new TeamMemberId('wrong-owner-id'));
    }
}
