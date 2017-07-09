<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

class RegisterUserCommand
{
    private $userId;
    private $email;
    private $password;
    private $name;
    private $timezone;

    public function __construct(string $userId, string $email, string $password, string $name, string $timezone)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->timezone = $timezone;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function timezone(): string
    {
        return $this->timezone;
    }
}
