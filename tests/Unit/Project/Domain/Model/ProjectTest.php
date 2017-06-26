<?php

namespace Tests\Unit\Project\Domain\Model\Project;

use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Event\Event;
use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoList;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Collaborator\Creator;
use Teamo\Project\Domain\Model\Tenant\TenantId;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    /**
     * @var Project
     */
    private $project;

    public function setUp()
    {
        $this->project = new Project(new TenantId('id-1'), new ProjectId('id-1'), 'My Project');
    }

    public function testConstructedProjectIsValid()
    {
        $tenantId = new TenantId('tenant');
        $projectId = new ProjectId('project');

        $project = new Project($tenantId, $projectId, 'My Project');

        $this->assertSame($tenantId, $project->tenantId());
        $this->assertSame($projectId, $project->projectId());
        $this->assertEquals('My Project', $project->name());
    }

    public function testProjectCanStartDiscussion()
    {
        $author = new Author('id-1', 'John Doe');
        $discussion = $this->project->startDiscussion($author, 'New Discussion', 'Discussion content');

        $this->assertInstanceOf(Discussion::class, $discussion);
    }

    public function testProjectCanCreateTodoList()
    {
        $creator = new Creator('id-1', 'John Doe');
        $todoList = $this->project->createTodoList($creator, 'New Todo List');

        $this->assertInstanceOf(TodoList::class, $todoList);
    }

    public function testProjectCanScheduleEvent()
    {
        $creator = new Creator('id-1', 'John Doe');
        $event = $this->project->scheduleEvent($creator, 'My Event', 'Event details', '2020-01-01 00:00:00');

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
