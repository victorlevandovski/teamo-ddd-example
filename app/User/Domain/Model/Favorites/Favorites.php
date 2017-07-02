<?php
declare(strict_types=1);

namespace Teamo\User\Domain\Model\Favorites;

use Teamo\Common\Domain\ValueObject;
use Teamo\Project\Domain\Model\Project\TodoList\TodoId;

class Favorites extends ValueObject
{
    private $todoIds;

    public function __construct(array $todoIds)
    {
        $this->todoIds = $todoIds;
    }

    /**
     * @return TodoId[]
     */
    public function favoriteTodos(): array
    {
        return $this->todoIds;
    }

    public function addTodoToFavorites(TodoId $todoId): self
    {
        $this->todoIds[] = $todoId;

        return new self($this->todoIds);
    }
}
