<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Query\TodoList;

use Illuminate\Support\Collection;
use Teamo\Project\Application\Query\ProjectPayload;
use Teamo\Project\Domain\Model\Project\TodoList\TodoList;
use Teamo\Project\Domain\Model\Project\Project;

class TodoListsPayload extends ProjectPayload
{
    private $todoLists;

    public function __construct(Project $project, Collection $todoLists)
    {
        parent::__construct($project);

        $this->todoLists = $todoLists;
    }

    /** @return Collection|TodoList[] */
    public function todoLists(): Collection
    {
        return $this->todoLists;
    }
}
