<?php
declare(strict_types=1);

namespace Teamo\User\Application\Command\User;

class UpdateUserProfileCommand
{
    private $userId;
    private $name;
    private $timezone;
    private $dateFormat;
    private $timeFormat;
    private $firstDayOfWeek;
    private $language;

    public function __construct(
        string $userId,
        string $name,
        string $timezone,
        string $dateFormat,
        int $timeFormat,
        int $firstDayOfWeek,
        string $language
    ) {
        $this->userId = $userId;
        $this->name = $name;
        $this->timezone = $timezone;
        $this->dateFormat = $dateFormat;
        $this->timeFormat = $timeFormat;
        $this->firstDayOfWeek = $firstDayOfWeek;
        $this->language = $language;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function timezone(): string
    {
        return $this->timezone;
    }

    public function dateFormat(): string
    {
        return $this->dateFormat;
    }

    public function timeFormat(): int
    {
        return $this->timeFormat;
    }

    public function firstDayOfWeek(): int
    {
        return $this->firstDayOfWeek;
    }

    public function language(): string
    {
        return $this->language;
    }
}
