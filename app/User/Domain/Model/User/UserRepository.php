<?php
declare(strict_types=1);

namespace Teamo\User\Domain\Model\User;

interface UserRepository
{
    public function add(User $user);

    public function remove(User $user);

    public function ofId(UserId $userId): User;
}
