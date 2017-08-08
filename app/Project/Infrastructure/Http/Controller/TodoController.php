<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Http\Controller;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Teamo\Common\Application\CommandBus;
use Teamo\Common\Http\Controller;
use Teamo\Project\Application\Command\TodoList\AddTodoCommand;
use Teamo\Project\Application\Command\TodoList\CompleteTodoCommand;
use Teamo\Project\Application\Command\TodoList\PostTodoCommentCommand;
use Teamo\Project\Application\Command\TodoList\RemoveAttachmentOfTodoCommentCommand;
use Teamo\Project\Application\Command\TodoList\RemoveTodoCommand;
use Teamo\Project\Application\Command\TodoList\RemoveTodoCommentCommand;
use Teamo\Project\Application\Command\TodoList\ReorderTodoCommand;
use Teamo\Project\Application\Command\TodoList\UncheckTodoCommand;
use Teamo\Project\Application\Command\TodoList\UpdateTodoCommand;
use Teamo\Project\Application\Command\TodoList\UpdateTodoCommentCommand;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoCommentRepository;
use Teamo\Project\Domain\Model\Project\TodoList\TodoId;
use Teamo\Project\Infrastructure\Http\Request\PostCommentRequest;
use Teamo\Project\Infrastructure\Http\Request\PostTodoRequest;
use Teamo\Project\Infrastructure\Http\Request\UpdateCommentRequest;

class TodoController extends Controller
{
    public function ajaxAddTodo(string $projectId, string $todoListId, PostTodoRequest $request, CommandBus $commandBus)
    {
        $todoId = Uuid::uuid4()->toString();

        $command = new AddTodoCommand(
            $projectId,
            $todoListId,
            $todoId,
            $this->authenticatedId(),
            $request->get('name'),
            (string) $request->get('assignee_id'),
            (string) $request->deadline()
        );
        $commandBus->handle($command);

        $name = \Html::linkRoute('project.todo_list.todo.show', $request->get('name'), [$projectId, $todoListId, $todoId]);

        return response()->json(['status' => true, 'id' => $todoId, 'name' => (string) $name]);
    }

    public function ajaxEditTodo(string $projectId, string $todoListId, string $todoId, PostTodoRequest $request, CommandBus $commandBus)
    {
        $command = new UpdateTodoCommand(
            $projectId,
            $todoListId,
            $todoId,
            $this->authenticatedId(),
            $request->get('name'),
            (string) $request->get('assignee_id'),
            (string) $request->deadline()
        );
        $commandBus->handle($command);

        return response()->json(['status' => true]);
    }

    public function ajaxCheckTodo(string $projectId, string $todoListId, string $todoId, Request $request, CommandBus $commandBus)
    {
        if ($request->get('checked', false)) {
            $command = new CompleteTodoCommand($projectId, $todoListId, $todoId, $this->authenticatedId());
            $commandBus->handle($command);
        } else {
            $command = new UncheckTodoCommand($projectId, $todoListId, $todoId, $this->authenticatedId());
            $commandBus->handle($command);
        }

        return response()->json(['status' => true]);
    }

    public function ajaxSortTodo(string $projectId, string $todoListId, string $todoId, Request $request, CommandBus $commandBus)
    {
        if ($request->has('order')) {
            $command = new ReorderTodoCommand(
                $projectId,
                $todoListId,
                $todoId,
                $this->authenticatedId(),
                $request->get('order')
            );
            $commandBus->handle($command);
        }

        return response()->json(['status' => true]);
    }

    public function ajaxDeleteTodo(string $projectId, string $todoListId, string $todoId, CommandBus $commandBus)
    {
        $command = new RemoveTodoCommand($projectId, $todoListId, $todoId, $this->authenticatedId());
        $commandBus->handle($command);

        return response()->json(['status' => true]);
    }


    /* Comments */


    public function storeComment(string $projectId, string $todoListId, string $todoId, PostCommentRequest $request, CommandBus $commandBus)
    {
        $commentId = Uuid::uuid4()->toString();

        $command = new PostTodoCommentCommand(
            $projectId,
            $todoListId,
            $todoId,
            $commentId,
            $this->authenticatedId(),
            (string) $request->get('content'),
            $request->attachments()
        );
        $commandBus->handle($command);

        return redirect(route('project.todo_list.todo.show', [$projectId, $todoListId, $todoId]) . '#comment-' . $commentId);
    }

    public function editComment(string $projectId, string $todoListId, string $commentId, TodoCommentRepository $commentRepository)
    {
        return view('project.todo_list.edit_comment', [
            'todoListId' => $todoListId,
            'comment' => $commentRepository->ofId(new CommentId($commentId), new TodoId($todoListId)),
        ]);
    }

    public function updateComment(string $projectId, string $todoListId, string $todoId, string $commentId, UpdateCommentRequest $request, CommandBus $commandBus)
    {
        $command = new UpdateTodoCommentCommand(
            $projectId,
            $todoListId,
            $todoId,
            $commentId,
            $this->authenticatedId(),
            $request->get('content')
        );
        $commandBus->handle($command);

        return redirect(route('project.todo_list.todo.show', [$projectId, $todoListId, $todoId]) . '#comment-' . $commentId);
    }

    public function ajaxDestroyComment(string $projectId, string $todoListId, string $todoId, string $commentId, CommandBus $commandBus)
    {
        try {
            $command = new RemoveTodoCommentCommand(
                $projectId,
                $todoListId,
                $todoId,
                $commentId,
                $this->authenticatedId()
            );
            $commandBus->handle($command);

            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage()]);
        }
    }

    public function ajaxDestroyCommentAttachment(string $projectId, string $todoListId, string $todoId, string $commentId, string $attachmentId, CommandBus $commandBus)
    {
        try {
            $command = new RemoveAttachmentOfTodoCommentCommand(
                $projectId,
                $todoListId,
                $todoId,
                $commentId,
                $attachmentId,
                $this->authenticatedId()
            );
            $commandBus->handle($command);

            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage()]);
        }
    }
}
