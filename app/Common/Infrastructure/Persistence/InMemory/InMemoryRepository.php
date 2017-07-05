<?php
declare(strict_types=1);

namespace Teamo\Common\Infrastructure\Persistence\InMemory;

use Illuminate\Support\Collection;

abstract class InMemoryRepository
{
    protected $items;

    public function __construct()
    {
        $this->items = new Collection();
    }

    protected function findOrFail(string $id, string $message)
    {
        $this->assertHas($id, $message);

        return $this->items->get($id);
    }

    protected function assertHas(string $id, string $message)
    {
        if (!$this->items->has($id)) {
            throw new \InvalidArgumentException($message);
        }
    }
}
