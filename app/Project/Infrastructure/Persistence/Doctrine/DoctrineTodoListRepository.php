<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\Doctrine;

use Illuminate\Support\Collection;
use Teamo\Common\Infrastructure\Persistence\DoctrineRepository;
use Teamo\Project\Domain\Model\Project\TodoList\TodoList;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;

class DoctrineTodoListRepository extends DoctrineRepository implements TodoListRepository
{
    public function add(TodoList $todoList)
    {
        $this->getEntityManager()->persist($todoList);
    }

    public function remove(TodoList $todoList)
    {
        $this->getEntityManager()->remove($todoList);
    }

    public function ofId(TodoListId $todoListId, ProjectId $projectId): TodoList
    {
        /** @var TodoList $todoList */
        $todoList = $this->findOneBy(['todoListId' => $todoListId, 'projectId' => $projectId]);

        if (null === $todoList) {
            throw new \InvalidArgumentException('Invalid todo list id or project id');
        }

        return $todoList;
    }

    public function allActive(ProjectId $projectId): Collection
    {
        return new Collection($this->findBy(['projectId' => $projectId, 'archived' => 0]));
    }

    public function allArchived(ProjectId $projectId): Collection
    {
        return new Collection($this->findBy(['projectId' => $projectId, 'archived' => 1]));
    }
}
