<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

class RemoveUserAvatarCommand
{
    private $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function userId(): string
    {
        return $this->userId;
    }
}
