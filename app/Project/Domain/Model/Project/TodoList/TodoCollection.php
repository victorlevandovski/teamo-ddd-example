<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\TodoList;

use Illuminate\Support\Collection;

class TodoCollection extends Collection
{
    public function ofId(TodoId $todoId): Todo
    {
        return $this->get($todoId->id());
    }

    public function reorder(string $id, int $position)
    {
        $position = min(intval($position), $this->count());
        if ($position < 1) {
            return;
        }

        $current_position = 1;
        $reorderedItems = [];

        foreach ($this->items as $item) {
            /** @var Todo $item */
            if ($current_position == $position) {
                $reorderedItems[$id] = $this->items[$id];
                $current_position++;
            }
            if ($item->todoId()->id() != $id) {
                $reorderedItems[$item->todoId()->id()] = $this->items[$item->todoId()->id()];
                $current_position++;
            }
        }

        if ($position == $this->count()) {
            $reorderedItems[$id] = $this->items[$id];
        }

        $this->items = $reorderedItems;
    }
}
