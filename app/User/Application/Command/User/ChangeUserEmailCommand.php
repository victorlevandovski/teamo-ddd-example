<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

class ChangeUserEmailCommand
{
    private $userId;
    private $email;
    private $currentPassword;

    public function __construct(string $userId, string $email, string $currentPassword)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->currentPassword = $currentPassword;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function currentPassword(): string
    {
        return $this->currentPassword;
    }
}
