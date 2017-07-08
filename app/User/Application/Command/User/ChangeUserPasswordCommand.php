<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

class ChangeUserPasswordCommand
{
    private $userId;
    private $currentPassword;
    private $newPassword;

    public function __construct(string $userId, string $newPassword, string $currentPassword)
    {
        $this->userId = $userId;
        $this->newPassword = $newPassword;
        $this->currentPassword = $currentPassword;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function newPassword(): string
    {
        return $this->newPassword;
    }

    public function currentPassword(): string
    {
        return $this->currentPassword;
    }
}
