<?php
declare(strict_types=1);

namespace Teamo\User\Domain\Model\User;

use Teamo\Common\Domain\ValueObject;

/**
 * Class Preferences
 * @package Teamo\User\Domain\Model\User
 *
 * Properties of this class are intentionally simplified. It would be more correct to set
 * most of properties as value objects, since they contain not just simple strings or
 * integers, but some state representations. Anyway, since it is just preferences
 * and not a core domain model, I decided to simplify this structure for now.
 */
class Preferences extends ValueObject
{
    private $language;
    private $timezone;
    private $dateFormat;
    private $timeFormat;
    private $firstDayOfWeek;
    private $showTodoListsAs;
    private $notifications;

    public function __construct(
        string $language,
        string $timezone,
        string $dateFormat,
        int $timeFormat,
        int $firstDayOfWeek,
        string $showTodoListsAs,
        Notifications $notifications
    ) {
        $this->setLanguage($language);
        $this->setTimezone($timezone);
        $this->setDateFormat($dateFormat);
        $this->setTimeFormat($timeFormat);
        $this->setFirstDayOfWeek($firstDayOfWeek);
        $this->setShowTodoListsAs($showTodoListsAs);
        $this->setNotifications($notifications);
    }

    public static function default(string $timezone)
    {
        $notifications = new Notifications(true, true, true, true, true, true, true);

        return new self('en', $timezone, 'dd.mm.yyyy', 24, 1, 'todo_lists', $notifications);
    }

    public function updateNotifications(Notifications $notifications): self
    {
        return new self(
            $this->language(),
            $this->timezone(),
            $this->dateFormat(),
            $this->timeFormat(),
            $this->firstDayOfWeek(),
            $this->showTodoListsAs(),
            $notifications
        );
    }

    public function language(): string
    {
        return $this->language;
    }

    public function timezone(): string
    {
        return $this->timezone;
    }

    public function timeFormat(): int
    {
        return $this->timeFormat;
    }

    public function dateFormat(): string
    {
        return $this->dateFormat;
    }

    public function firstDayOfWeek(): int
    {
        return $this->firstDayOfWeek;
    }

    public function showTodoListsAs(): string
    {
        return $this->showTodoListsAs;
    }

    public function notifications(): Notifications
    {
        return $this->notifications;
    }

    private function setLanguage(string $language)
    {
        if (!in_array($language, ['en', 'us'])) {
            throw new \InvalidArgumentException('Unknown language');
        }

        $this->language = $language;
    }

    private function setTimezone(string $timezone)
    {
        if (!$timezone) {
            $timezone = 'UTC';
        }

        $this->timezone = $timezone;
    }

    private function setTimeFormat(int $timeFormat)
    {
        if (!in_array($timeFormat, [12, 24])) {
            throw new \InvalidArgumentException('Unknown time format');
        }

        $this->timeFormat = $timeFormat;
    }

    private function setDateFormat(string $dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    private function setFirstDayOfWeek(int $firstDayOfWeek)
    {
        if ($firstDayOfWeek < 1 || $firstDayOfWeek > 7) {
            throw new \InvalidArgumentException('Invalid first day of week');
        }

        $this->firstDayOfWeek = $firstDayOfWeek;
    }

    private function setShowTodoListsAs(string $showTodoListsAs)
    {
        if (!in_array($showTodoListsAs, ['todo_lists', 'todos', 'my_todos'])) {
            throw new \InvalidArgumentException('Unknown todo lists viewer option');
        }

        $this->showTodoListsAs = $showTodoListsAs;
    }

    private function setNotifications(Notifications $notifications)
    {
        $this->notifications = $notifications;
    }
}
