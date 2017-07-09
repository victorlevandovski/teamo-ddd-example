<?php
declare(strict_types=1);

namespace Teamo\User\Infrastructure\Persistence\InMemory;

use Teamo\Common\Infrastructure\Persistence\InMemory\InMemoryRepository;
use Teamo\User\Domain\Model\User\User;
use Teamo\User\Domain\Model\User\UserId;
use Teamo\User\Domain\Model\User\UserRepository;

class InMemoryUserRepository extends InMemoryRepository implements UserRepository
{
    public function add(User $user)
    {
        $this->items->put($user->userId()->id(), $user);
    }

    public function remove(User $user)
    {
        $this->items->forget($user->userId()->id());
    }

    public function ofId(UserId $userId): User
    {
        return $this->findOrFail($userId->id(), 'Invalid user id');
    }
}
