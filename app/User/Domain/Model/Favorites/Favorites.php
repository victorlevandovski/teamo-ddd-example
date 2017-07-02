<?php
declare(strict_types=1);

namespace Teamo\User\Domain\Model\Favorites;

use Teamo\Common\Domain\ValueObject;
use Teamo\Project\Domain\Model\Project\TodoList\TodoId;

class Favorites extends ValueObject
{
    /**
     * @var TodoId[]
     */
    private $todoIds;

    /**
     * @param TodoId[] $todoIds
     */
    public function __construct($todoIds)
    {
        $this->todoIds = $todoIds;
    }

    /**
     * @return TodoId[]
     */
    public function favoriteTodos()
    {
        return $this->todoIds;
    }

    public function addTodoToFavorites(TodoId $todoId)
    {
        $this->todoIds[] = $todoId;

        return new self($this->todoIds);
    }
}
