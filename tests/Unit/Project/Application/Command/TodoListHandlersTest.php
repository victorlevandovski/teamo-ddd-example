<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Application\Command;

use Illuminate\Support\Collection;
use Teamo\Project\Application\Command\TodoList\ArchiveTodoListCommand;
use Teamo\Project\Application\Command\TodoList\ArchiveTodoListHandler;
use Teamo\Project\Application\Command\TodoList\CreateTodoListCommand;
use Teamo\Project\Application\Command\TodoList\CreateTodoListHandler;
use Teamo\Project\Application\Command\TodoList\PostTodoCommentCommand;
use Teamo\Project\Application\Command\TodoList\PostTodoCommentHandler;
use Teamo\Project\Application\Command\TodoList\RemoveAttachmentOfTodoCommentCommand;
use Teamo\Project\Application\Command\TodoList\RemoveAttachmentOfTodoCommentHandler;
use Teamo\Project\Application\Command\TodoList\RemoveTodoCommentCommand;
use Teamo\Project\Application\Command\TodoList\RemoveTodoCommentHandler;
use Teamo\Project\Application\Command\TodoList\RemoveTodoListCommand;
use Teamo\Project\Application\Command\TodoList\RemoveTodoListHandler;
use Teamo\Project\Application\Command\TodoList\RenameTodoListCommand;
use Teamo\Project\Application\Command\TodoList\RenameTodoListHandler;
use Teamo\Project\Application\Command\TodoList\RestoreTodoListCommand;
use Teamo\Project\Application\Command\TodoList\RestoreTodoListHandler;
use Teamo\Project\Application\Command\TodoList\UpdateTodoCommentCommand;
use Teamo\Project\Application\Command\TodoList\UpdateTodoCommentHandler;
use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentManager;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoList;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Team\TeamMember;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryTeamMemberRepository;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryProjectRepository;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryTodoCommentRepository;
use Teamo\Project\Infrastructure\Persistence\InMemory\InMemoryTodoListRepository;
use Tests\TestCase;

class TodoListHandlersTest extends TestCase
{
    /** @var InMemoryProjectRepository */
    private $projectRepository;

    /** @var InMemoryTodoListRepository */
    private $todoListRepository;

    /** @var  InMemoryTodoCommentRepository */
    private $commentRepository;

    /** @var AttachmentManager */
    private $attachmentManager;

    /** @var  TodoList */
    private $todoList;

    /** @var Project */
    private $project;

    public function setUp()
    {
        parent::setUp();

        $this->projectRepository = new InMemoryProjectRepository();
        $this->todoListRepository = new InMemoryTodoListRepository();
        $this->commentRepository = new InMemoryTodoCommentRepository();

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

        $this->todoList = $this->project->createTodoList(
            new TodoListId('t-l-1'),
            $owner,
            'Todo List 1'
        );
        $this->todoList->addTodo(new TodoId('t-1'), $owner, 'Todo 1');
        $this->todoListRepository->add($this->todoList);
    }

    public function testCreateTodoListCommandAddsTodoListToRepository()
    {
        $command = new CreateTodoListCommand('p-1', 'test-todo-list-1', 't-1', 'Name');
        $handler = new CreateTodoListHandler($this->todoListRepository, $this->projectRepository);
        $handler->handle($command);

        $todoList = $this->todoListRepository->ofId(new TodoListId('test-todo-list-1'), new ProjectId('p-1'));

        $this->assertEquals('t-1', $todoList->creator()->id());
        $this->assertEquals('Name', $todoList->name());
    }

    public function testRenameTodoListCommandRenamesTodoList()
    {
        $command = new RenameTodoListCommand('p-1', 't-l-1', 't-1', 'New name');
        $handler = new RenameTodoListHandler($this->todoListRepository);
        $handler->handle($command);

        $todoList = $this->todoListRepository->ofId(new TodoListId('t-l-1'), new ProjectId('p-1'));
        $this->assertEquals('New name', $todoList->name());
    }

    public function testArchiveTodoListHandlerArchivesTodoList()
    {
        $command = new ArchiveTodoListCommand('p-1', 't-l-1', 't-1');
        $handler = new ArchiveTodoListHandler($this->todoListRepository);
        $handler->handle($command);

        $todoList = $this->todoListRepository->ofId(new TodoListId('t-l-1'), new ProjectId('p-1'));
        $this->assertTrue($todoList->isArchived());
    }

    public function testRestoreTodoListHandlerRestoresTodoList()
    {
        $command = new RestoreTodoListCommand('p-1', 't-l-1', 't-1');
        $handler = new RestoreTodoListHandler($this->todoListRepository);
        $handler->handle($command);

        $todoList = $this->todoListRepository->ofId(new TodoListId('t-l-1'), new ProjectId('p-1'));
        $this->assertFalse($todoList->isArchived());
    }

    public function testRemoveTodoListHandlerRemovesTodoList()
    {
        $command = new RemoveTodoListCommand('p-1', 't-l-1', 't-1');
        $handler = new RemoveTodoListHandler($this->todoListRepository);
        $handler->handle($command);

        $this->expectException(\InvalidArgumentException::class);
        $this->todoListRepository->ofId(new TodoListId('t-l-1'), new ProjectId('p-1'));
    }

    public function testTodoListCommentHandlersDoTheirJob()
    {
        $command = new PostTodoCommentCommand('p-1', 't-l-1', 't-1', 'c-1', 't-1', 'Comment', []);
        $handler = new PostTodoCommentHandler($this->todoListRepository, $this->commentRepository, $this->attachmentManager);
        $handler->handle($command);
        $comment = $this->commentRepository->ofId(new CommentId('c-1'), new TodoId('t-1'));
        $this->assertEquals('t-1', $comment->author()->id());
        $this->assertEquals('t-1', $comment->todoId()->id());
        $this->assertEquals('Comment', $comment->content());
        $this->assertEquals('image.jpg', $comment->attachments()->first()->name());

        $command = new UpdateTodoCommentCommand('p-1', 't-l-1', 't-1', 'c-1', 't-1', 'New comment');
        $handler = new UpdateTodoCommentHandler($this->commentRepository);
        $handler->handle($command);
        $comment = $this->commentRepository->ofId(new CommentId('c-1'), new TodoId('t-1'));
        $this->assertEquals('New comment', $comment->content());

        $command = new RemoveAttachmentOfTodoCommentCommand('p-1', 't-l-1', 't-1', 'c-1', 'a-1', 't-1');
        $handler = new RemoveAttachmentOfTodoCommentHandler($this->commentRepository);
        $handler->handle($command);
        $comment = $this->commentRepository->ofId(new CommentId('c-1'), new TodoId('t-1'));
        $this->assertTrue($comment->attachments()->isEmpty());

        $command = new RemoveTodoCommentCommand('p-1', 't-l-1', 't-1', 'c-1', 't-1');
        $handler = new RemoveTodoCommentHandler($this->commentRepository);
        $handler->handle($command);
        $this->expectException(\InvalidArgumentException::class);
        $this->commentRepository->ofId(new CommentId('c-1'), new TodoId('t-1'));
    }
}
