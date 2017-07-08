<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

class UpdateUserAvatarCommand
{
    private $userId;
    private $file;

    public function __construct(string $userId, string $file)
    {
        $this->userId = $userId;
        $this->file = $file;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function file(): string
    {
        return $this->file;
    }
}
