<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

class RegisterUserCommand
{
    private $email;
    private $password;
    private $name;
    private $timezone;

    public function __construct(string $email, string $password, string $name, string $timezone)
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->timezone = $timezone;
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
