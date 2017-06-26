<?php

namespace Tests\Unit\Project\Domain\Model\Project;

use Teamo\Project\Domain\Model\Collaborator\Assignee;
use Teamo\Project\Domain\Model\Project\TodoList\Todo;
use Teamo\Project\Domain\Model\Project\TodoList\TodoCollection;
use Teamo\Project\Domain\Model\Project\TodoList\TodoId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoList;
use Teamo\Project\Domain\Model\Project\TodoList\TodoComment;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Collaborator\Creator;
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
            new Creator('id-1', 'John Doe'),
            'My Todo List'
        );
    }

    public function testConstructedTodoListIsValid()
    {
        $projectId = new ProjectId();
        $todoListId = new TodoListId();
        $creator = new Creator('author-1', 'John Doe');

        $todos = new TodoCollection(new Todo($todoListId, new TodoId(), 'Todo'));
        //$attachments = new Collection(new Attachment(new AttachmentId(), 'Attachment.txt'));

        $todoList = new TodoList($projectId, $todoListId, $creator, 'Name', $todos);

        $this->assertSame($projectId, $todoList->projectId());
        $this->assertSame($todoListId, $todoList->todoListId());
        $this->assertSame($creator, $todoList->creator());
        $this->assertEquals('Name', $todoList->name());
        $this->assertSame($todos, $todoList->todos());
    }

    public function testConstructedTodoIsValid()
    {
        $todoListId = new TodoListId();
        $todoId = new TodoId();
        $assignee = new Assignee('author-1', 'John Doe');

        $todo = new Todo($todoListId, $todoId, 'Name', $assignee, '2020-01-01 00:00:00', true);

        $this->assertSame($todoListId, $todo->todoListId());
        $this->assertSame($todoId, $todo->todoId());
        $this->assertEquals('Name', $todo->name());
        $this->assertSame($assignee, $todo->assignee());
        $this->assertEquals('2020-01-01 00:00:00', $todo->deadline());
        $this->assertTrue($todo->isCompleted());
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
        $this->todoList->addTodo('My Todo 1', null, null);
        $this->todoList->addTodo('My Todo 2', new Assignee('assignee-1', 'John Doe'), null);
        $this->todoList->addTodo('My Todo 3', new Assignee('assignee-1', 'John Doe'), '2020-01-01 00:00:00');

        $this->assertCount(3, $this->todoList->todos());
    }

    public function testTodoCanBeRemoved()
    {
        $todoId = $this->todoList->addTodo('My Todo 1', null, null);
        $this->assertCount(1, $this->todoList->todos());

        $this->todoList->removeTodo($todoId);
        $this->assertEmpty($this->todoList->todos());
    }

    public function testTodoCanBeReordered()
    {
        $todoId0 = $this->todoList->addTodo('My Todo 0');
        $todoId1 = $this->todoList->addTodo('My Todo 1');
        $todoId2 = $this->todoList->addTodo('My Todo 2');

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

    public function testTodoCanBeUpdated()
    {
        $todoId = $this->todoList->addTodo('My Todo 1', null, null);
        $todo = $this->todoList->todos()->ofId($todoId);

        $this->assertEquals('My Todo 1', $todo->name());
        $this->assertNull($todo->assignee());
        $this->assertNull($todo->deadline());

        $newAssignee = new Assignee('a-1', 'John Doe');
        $todo->update('New Todo 1', $newAssignee, '2020-01-01 00:00:00');
        $this->assertEquals('New Todo 1', $todo->name());
        $this->assertEquals($newAssignee, $todo->assignee());
        $this->assertEquals('2020-01-01 00:00:00', $todo->deadline());
    }

    public function testTodoCanBeCompletedAndUncompleted()
    {
        $todoId = $this->todoList->addTodo('My Todo');
        $this->assertFalse($this->todoList->todos()->ofId($todoId)->isCompleted());

        $this->todoList->todos()->ofId($todoId)->complete();
        $this->assertTrue($this->todoList->todos()->ofId($todoId)->isCompleted());

        $this->todoList->todos()->ofId($todoId)->uncomplete();
        $this->assertFalse($this->todoList->todos()->ofId($todoId)->isCompleted());
    }

    public function testTodoCanBeCommented()
    {
        $todoId = $this->todoList->addTodo('My Todo');
        $comment = $this->todoList->todos()->ofId($todoId)->comment(new Author('id-1', 'John Doe'), 'Comment content');

        $this->assertInstanceOf(TodoComment::class, $comment);
    }
}