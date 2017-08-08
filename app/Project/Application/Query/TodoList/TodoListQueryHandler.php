<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Query\TodoList;

use Teamo\Project\Domain\Model\Project\TodoList\TodoCommentRepository;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class TodoListQueryHandler
{
    private $projectRepository;
    private $todoListRepository;
    private $commentRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        TodoListRepository $todoListRepository,
        TodoCommentRepository $commentRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->todoListRepository = $todoListRepository;
        $this->commentRepository = $commentRepository;
    }

    public function todoList(TodoListQuery $query): TodoListPayload
    {
        $projectId = new ProjectId($query->projectId());
        $todoListId = new TodoListId($query->todoListId());

        return new TodoListPayload(
            $this->projectRepository->ofId($projectId, new TeamMemberId($query->teamMemberId())),
            $this->todoListRepository->ofId($todoListId, $projectId)
        );
    }

    public function allTodoLists(AllTodoListsQuery $query): TodoListsPayload
    {
        $projectId = new ProjectId($query->projectId());

        if (!$query->archived()) {
            $todoLists = $this->todoListRepository->allActive($projectId);
        } else {
            $todoLists = $this->todoListRepository->allArchived($projectId);
        }

        return new TodoListsPayload(
            $this->projectRepository->ofId($projectId, new TeamMemberId($query->teamMemberId())),
            $todoLists
        );
    }
}
