<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\TodoList;

use Teamo\Project\Domain\Model\Project\TodoList\TodoListRepository;

abstract class TodoListHandler
{
    protected $todoListRepository;

    public function __construct(TodoListRepository $todoListRepository)
    {
        $this->todoListRepository = $todoListRepository;
    }
}
