<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Domain\Model\Project;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\TodoList\Todo;
use Teamo\Project\Domain\Model\Project\TodoList\TodoId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoList;
use Teamo\Project\Domain\Model\Project\TodoList\TodoComment;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    /**
     * @var TodoList
     */
    private $todoList;

    public function setUp()
    {
        $this->todoList = new TodoList(
            new ProjectId('id-1'),
            new TodoListId('id-1'),
            new TeamMemberId('id-1'),
            'My Todo List'
        );
    }

    public function testConstructedTodoListIsValid()
    {
        $projectId = new ProjectId('p-1');
        $todoListId = new TodoListId('tl-1');
        $creatorId = new TeamMemberId('creator-1');

        $todoList = new TodoList($projectId, $todoListId, $creatorId, 'Name');

        $this->assertSame($projectId, $todoList->projectId());
        $this->assertSame($todoListId, $todoList->todoListId());
        $this->assertSame($creatorId, $todoList->creatorId());
        $this->assertEquals('Name', $todoList->name());
    }

    public function testConstructedTodoIsValid()
    {
        $todoListId = new TodoListId('tl-1');
        $todoId = new TodoId('t-1');

        $todo = new Todo($todoListId, $todoId, 'Name');

        $this->assertSame($todoListId, $todo->todoListId());
        $this->assertSame($todoId, $todo->todoId());
        $this->assertEquals('Name', $todo->name());
    }

    public function testTodoListCanBeRenamed()
    {
        $this->assertEquals('My Todo List', $this->todoList->name());

        $this->todoList->rename('New Todo List');
        $this->assertEquals('New Todo List', $this->todoList->name());
    }

    public function testTodoListCanBeArchivedAndRestored()
    {
        $this->assertFalse($this->todoList->isArchived());

        $this->todoList->archive();
        $this->assertTrue($this->todoList->isArchived());

        $this->todoList->restore();
        $this->assertFalse($this->todoList->isArchived());
    }

    public function testTodoCanBeAdded()
    {
        $this->todoList->addTodo(new TodoId('t-1'), 'My Todo 1');
        $this->todoList->addTodo(new TodoId('t-2'), 'My Todo 2');
        $this->todoList->addTodo(new TodoId('t-3'), 'My Todo 3');

        $this->assertCount(3, $this->todoList->todos());
    }

    public function testTodoCanBeRemoved()
    {
        $todoId = $this->todoList->addTodo(new TodoId('t-1'), 'My Todo 1');
        $this->assertCount(1, $this->todoList->todos());

        $this->todoList->removeTodo($todoId);
        $this->assertEmpty($this->todoList->todos());
    }

    public function testTodoCanBeReordered()
    {
        $todoId0 = $this->todoList->addTodo(new TodoId('t-0'), 'My Todo 0');
        $todoId1 = $this->todoList->addTodo(new TodoId('t-1'), 'My Todo 1');
        $todoId2 = $this->todoList->addTodo(new TodoId('t-2'), 'My Todo 2');

        $this->todoList->reorderTodo($todoId2, 1);

        $todos = $this->todoList->todos()->values();
        $this->assertEquals($todoId2, $todos[0]->todoId());
        $this->assertEquals($todoId0, $todos[1]->todoId());
        $this->assertEquals($todoId1, $todos[2]->todoId());

        $this->todoList->reorderTodo($todoId0, 3);

        $todos = $this->todoList->todos()->values();
        $this->assertEquals($todoId2, $todos[0]->todoId());
        $this->assertEquals($todoId1, $todos[1]->todoId());
        $this->assertEquals($todoId0, $todos[2]->todoId());
    }

    public function testTodoCanBeRenamed()
    {
        $todoId = $this->todoList->addTodo(new TodoId('t-1'), 'My Todo 1');
        $todo = $this->todoList->todos()->ofId($todoId);

        $this->assertEquals('My Todo 1', $todo->name());
        //$this->assertNull($todo->assigneeId());
        //$this->assertNull($todo->deadline());

        //$newAssigneeId = new TeamMemberId('a-1');
        $todo->rename('New Todo 1');
        $this->assertEquals('New Todo 1', $todo->name());
        //$this->assertEquals($newAssigneeId, $todo->assigneeId());
        //$this->assertEquals('2020-01-01 00:00:00', $todo->deadline());
    }

    public function testTodoCanBeAssignedToTeamMember()
    {
        $todoId = $this->todoList->addTodo(new TodoId('t-1'), 'My Todo 1');
        $todo = $this->todoList->todos()->ofId($todoId);

        $this->assertNull($todo->assigneeId());

        $assigneeId = new TeamMemberId('tm-1');
        $todo->assignTo($assigneeId);
        $this->assertSame($assigneeId, $todo->assigneeId());

        $todo->removeAssignee();
        $this->assertNull($todo->assigneeId());
    }

    public function testDeadlineCanBeSetForTodo()
    {
        $todoId = $this->todoList->addTodo(new TodoId('t-1'), 'My Todo 1');
        $todo = $this->todoList->todos()->ofId($todoId);

        $this->assertNull($todo->deadline());

        $deadline = new Carbon();
        $todo->deadlineOn($deadline);
        $this->assertSame($deadline, $todo->deadline());

        $todo->removeDeadline();
        $this->assertNull($todo->deadline());
    }

    public function testTodoCanBeCompletedAndUncompleted()
    {
        $todoId = $this->todoList->addTodo(new TodoId('t-1'), 'My Todo');
        $this->assertFalse($this->todoList->todos()->ofId($todoId)->isCompleted());

        $this->todoList->todos()->ofId($todoId)->complete();
        $this->assertTrue($this->todoList->todos()->ofId($todoId)->isCompleted());

        $this->todoList->todos()->ofId($todoId)->uncheck();
        $this->assertFalse($this->todoList->todos()->ofId($todoId)->isCompleted());
    }

    public function testTodoCanBeCommented()
    {
        $todoId = $this->todoList->addTodo(new TodoId('t-1'), 'My Todo');
        $comment = $this->todoList->todos()->ofId($todoId)
            ->comment(new CommentId('1'), new TeamMemberId('id-1'), 'Comment content', new Collection());

        $this->assertInstanceOf(TodoComment::class, $comment);
    }
}
