<?php
declare(strict_types=1);

namespace Teamo\User\Domain\Model\User;

use Teamo\Common\Domain\ValueObject;

class Avatar extends ValueObject
{
    private $pathTo48pxAvatar;
    private $pathTo96pxAvatar;

    public function __construct(string $pathTo48pxAvatar, string $pathTo96pxAvatar)
    {
        $this->pathTo48pxAvatar = $pathTo48pxAvatar;
        $this->pathTo96pxAvatar = $pathTo96pxAvatar;
    }

    public static function default(): self
    {
        return new self('/avatars/avatar48.jpg', '/avatars/avatar96.jpg');
    }

    public function pathTo48pxAvatar(): string
    {
        return $this->pathTo48pxAvatar;
    }

    public function pathTo96pxAvatar(): string
    {
        return $this->pathTo96pxAvatar;
    }

    public function  isDefault(): bool
    {
        return $this->pathTo48pxAvatar == '/avatars/avatar48.jpg' && $this->pathTo96pxAvatar == '/avatars/avatar96.jpg';
    }
}
