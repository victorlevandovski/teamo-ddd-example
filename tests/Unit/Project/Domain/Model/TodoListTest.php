<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Domain\Model\Project;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
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
        $creator = new TeamMemberId('creator-1');
        $this->todoList->addTodo(new TodoId('t-1'), $creator, 'My Todo 1');
        $this->todoList->addTodo(new TodoId('t-2'), $creator, 'My Todo 2');
        $this->todoList->addTodo(new TodoId('t-3'), $creator, 'My Todo 3');

        $this->assertCount(3, $this->todoList->todos());
    }

    public function testTodoCanBeRemoved()
    {
        $creator = new TeamMemberId('creator-1');
        $todoId = new TodoId('t-1');

        $this->todoList->addTodo($todoId, $creator, 'My Todo 1');
        $this->assertCount(1, $this->todoList->todos());

        $this->todoList->removeTodo($todoId);
        $this->assertEmpty($this->todoList->todos());
    }

    public function testTodoCanBeReordered()
    {
        $creator = new TeamMemberId('creator-1');
        $todoId0 = new TodoId('t-0');
        $todoId1 = new TodoId('t-1');
        $todoId2 = new TodoId('t-2');
        $todoId3 = new TodoId('t-3');
        $this->todoList->addTodo($todoId0, $creator, 'My Todo 0');
        $this->todoList->addTodo($todoId1, $creator, 'My Todo 1');
        $this->todoList->addTodo($todoId2, $creator, 'My Todo 2');
        $this->todoList->addTodo($todoId3, $creator, 'My Todo 3');

        $this->todoList->reorderTodo($todoId2, 1);

        $todos = $this->todoList->todos();
        $this->assertEquals(2, $todos[0]->position());
        $this->assertEquals(3, $todos[1]->position());
        $this->assertEquals(1, $todos[2]->position());
        $this->assertEquals(4, $todos[3]->position());

        $this->todoList->reorderTodo($todoId0, 3);

        $todos = $this->todoList->todos();
        $this->assertEquals(3, $todos[0]->position());
        $this->assertEquals(2, $todos[1]->position());
        $this->assertEquals(1, $todos[2]->position());
        $this->assertEquals(4, $todos[3]->position());
    }

    public function testTodoCanBeRenamed()
    {
        $creator = new TeamMemberId('creator-1');
        $todoId = new TodoId('t-1');

        $this->todoList->addTodo($todoId, $creator, 'My Todo 1');

        $this->assertEquals('My Todo 1', $this->todoList->todos()[0]->name());

        $this->todoList->renameTodo($todoId, 'New Todo 1');
        $this->assertEquals('New Todo 1', $this->todoList->todos()[0]->name());
    }

    public function testTodoCanBeAssignedToTeamMember()
    {
        $creator = new TeamMemberId('creator-1');
        $todoId = new TodoId('t-1');

        $this->todoList->addTodo($todoId, $creator, 'My Todo 1');

        $this->assertNull($this->todoList->todos()[0]->assignee());

        $assigneeId = new TeamMemberId('tm-1');
        $this->todoList->assignTodoTo($todoId, $assigneeId);
        $this->assertSame($assigneeId, $this->todoList->todos()[0]->assignee());

        $this->todoList->removeTodoAssignee($todoId);
        $this->assertNull($this->todoList->todos()[0]->assignee());
    }

    public function testDeadlineCanBeSetForTodo()
    {
        $creator = new TeamMemberId('creator-1');
        $todoId = new TodoId('t-1');

        $this->todoList->addTodo($todoId, $creator, 'My Todo 1');

        $this->assertNull($this->todoList->todos()[0]->deadline());

        $deadline = new \DateTimeImmutable();
        $this->todoList->setTodoDeadline($todoId, $deadline);
        $this->assertSame($deadline, $this->todoList->todos()[0]->deadline());

        $this->todoList->removeTodoDeadline($todoId);
        $this->assertNull($this->todoList->todos()[0]->deadline());
    }

    public function testTodoCanBeCompletedAndUncompleted()
    {
        $creator = new TeamMemberId('creator-1');
        $todoId = new TodoId('t-1');

        $this->todoList->addTodo($todoId, $creator, 'My Todo');
        $this->assertFalse($this->todoList->todos()[0]->isCompleted());

        $this->todoList->completeTodo($todoId);
        $this->assertTrue($this->todoList->todos()[0]->isCompleted());

        $this->todoList->uncheckTodo($todoId);
        $this->assertFalse($this->todoList->todos()[0]->isCompleted());
    }

    public function testTodoCanBeCommented()
    {
        $creator = new TeamMemberId('creator-1');
        $todoId = new TodoId('t-1');

        $this->todoList->addTodo($todoId, $creator, 'My Todo');
        $comment = $this->todoList->todos()[0]->comment(new CommentId('1'), new TeamMemberId('id-1'), 'Comment content', new Collection());

        $this->assertInstanceOf(TodoComment::class, $comment);
    }
}
