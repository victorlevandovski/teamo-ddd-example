<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

class UpdateUserAvatarCommand
{
    private $userId;
    private $pathTo48pxAvatar;
    private $pathTo96pxAvatar;

    public function __construct(string $userId, string $pathTo48pxAvatar, string $pathTo96pxAvatar)
    {
        $this->userId = $userId;
        $this->pathTo48pxAvatar = $pathTo48pxAvatar;
        $this->pathTo96pxAvatar = $pathTo96pxAvatar;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function pathTo48pxAvatar(): string
    {
        return $this->pathTo48pxAvatar;
    }

    public function pathTo96pxAvatar(): string
    {
        return $this->pathTo96pxAvatar;
    }
}
