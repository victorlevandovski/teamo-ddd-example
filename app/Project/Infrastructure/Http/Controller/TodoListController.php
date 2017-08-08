<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Http\Controller;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Teamo\Common\Application\CommandBus;
use Teamo\Common\Http\Controller;
use Teamo\Project\Application\Command\TodoList\ArchiveTodoListCommand;
use Teamo\Project\Application\Command\TodoList\CreateTodoListCommand;
use Teamo\Project\Application\Command\TodoList\RemoveTodoListCommand;
use Teamo\Project\Application\Command\TodoList\RenameTodoListCommand;
use Teamo\Project\Application\Command\TodoList\RestoreTodoListCommand;
use Teamo\Project\Application\Query\TodoList\AllTodoListsQuery;
use Teamo\Project\Application\Query\TodoList\TodoListQuery;
use Teamo\Project\Application\Query\TodoList\TodoListQueryHandler;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Infrastructure\Http\Request\CreateTodoListRequest;
use Teamo\Project\Infrastructure\Http\Request\RenameTodoListRequest;

class TodoListController extends Controller
{
    public function index(string $projectId, Request $request, TodoListQueryHandler $queryHandler)
    {
        $query = new AllTodoListsQuery($projectId, $this->authenticatedId(), false);

        return view('project.todoList.index', [
            'todoListsPayload' => $queryHandler->allTodoLists($query),
            'listAs' => $request->get('list_as', 'todo')
        ]);
    }

    public function archive(string $projectId, TodoListQueryHandler $queryHandler)
    {
        $query = new AllTodoListsQuery($projectId, $this->authenticatedId(), true);

        return view('project.todoList.archive', [
            'todoListsPayload' => $queryHandler->allTodoLists($query),
        ]);
    }

    public function show(string $projectId, string $todoListId, TodoListQueryHandler $queryHandler)
    {
        $query = new TodoListQuery($projectId, $todoListId, $this->authenticatedId());

        return view('project.todoList.show', [
            'todoListPayload' => $queryHandler->todoList($query),
        ]);
    }

    public function create()
    {
        return view('project.todoList.create');
    }

    public function store(string $projectId, CreateTodoListRequest $request, CommandBus $commandBus)
    {
        $todoListId = Uuid::uuid4()->toString();

        $command = new CreateTodoListCommand($projectId, $todoListId, $this->authenticatedId(), $request->get('name'));
        $commandBus->handle($command);

        return redirect(route('project.todo_list.show', [$projectId, $todoListId]));
    }

    public function edit(string $projectId, string $todoListId, TodoListRepository $todoListRepository)
    {
        return view('project.todoList.edit', [
            'todoList' => $todoListRepository->ofId(new TodoListId($todoListId), new ProjectId($projectId)),
        ]);
    }

    public function update(string $projectId, string $todoListId, RenameTodoListRequest $request, CommandBus $commandBus)
    {
        $command = new RenameTodoListCommand($projectId, $todoListId, $this->authenticatedId(), $request->get('name'));
        $commandBus->handle($command);

        return redirect(route('project.todo_list.show', [$projectId, $todoListId]))
            ->with('success', trans('app.flash_todo_list_updated'));
    }

    public function archiveTodoList(string $projectId, string $todoListId, CommandBus $commandBus)
    {
        $command = new ArchiveTodoListCommand($projectId, $todoListId, $this->authenticatedId());
        $commandBus->handle($command);

        return redirect(route('project.todo_list.index', $projectId))->with('success', trans('app.flash_todoList_archived'));
    }

    public function restoreTodoList(string $projectId, string $todoListId, CommandBus $commandBus)
    {
        $command = new RestoreTodoListCommand($projectId, $todoListId, $this->authenticatedId());
        $commandBus->handle($command);

        return redirect(route('project.todo_list.show', [$projectId, $todoListId]))
            ->with('success', trans('app.flash_todo_list_restored'));
    }

    public function destroy(string $projectId, string $todoListId, CommandBus $commandBus)
    {
        $command = new RemoveTodoListCommand($projectId, $todoListId, $this->authenticatedId());
        $commandBus->handle($command);

        $route = strstr(\URL::previous(), 'archive') ? 'archive' : 'index';

        return redirect(route('project.todo_list.' . $route, $projectId))->with('success', trans('app.flash_todo_list_deleted'));
    }
}
