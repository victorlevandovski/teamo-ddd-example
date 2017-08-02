<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\TodoList;

use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class CreateTodoListHandler extends TodoListHandler
{
    private $projectRepository;

    public function __construct(
        TodoListRepository $todoListRepository,
        ProjectRepository $projectRepository
    ) {
        parent::__construct($todoListRepository);

        $this->projectRepository = $projectRepository;
    }

    public function handle(CreateTodoListCommand $command)
    {
        $teamMemberId = new TeamMemberId($command->creator());

        $project = $this->projectRepository->ofId(new ProjectId($command->projectId()), $teamMemberId);

        $todoList = $project->createTodoList(
            new TodoListId($command->todoListId()),
            $teamMemberId,
            $command->name()
        );

        $this->todoListRepository->add($todoList);
    }
}
