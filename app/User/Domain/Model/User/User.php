<?php
declare(strict_types=1);

namespace Teamo\User\Domain\Model\User;

use Teamo\Common\Domain\Entity;

class User extends Entity
{
    private $userId;
    private $name;
    private $email;
    private $password;
    private $rememberToken;
    private $preferences;
    private $notifications;
    private $avatar;

    public static function register(UserId $userId, string $email, string $password, string $name, string $timezone): self
    {
        return new self(
            $userId,
            $email,
            $password,
            $name,
            $timezone,
            Preferences::default($timezone),
            Notifications::default(),
            Avatar::default()
        );
    }

    public function rename(string $name)
    {
        $this->setName($name);
    }

    public function changeEmail(string $email)
    {
        $this->setEmail($email);
    }

    public function changePassword(string $password)
    {
        $this->setPassword($password);
    }

    public function updatePreferences(Preferences $preferences)
    {
        $this->setPreferences($preferences);
    }

    public function updateNotificationSettings(Notifications $notifications)
    {
        $this->setNotifications($notifications);
    }

    public function updateAvatar(Avatar $avatar)
    {
        $this->setAvatar($avatar);
    }

    public function removeAvatar()
    {
        $this->setAvatar(Avatar::default());
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function avatar(): Avatar
    {
        return $this->avatar;
    }

    public function preferences(): Preferences
    {
        return $this->preferences;
    }

    public function notifications(): Notifications
    {
        return $this->notifications;
    }

    public function rememberToken(): string
    {
        return $this->rememberToken;
    }

    private function __construct(
        UserId $userId,
        string $email,
        string $password,
        string $name,
        string $timezone,
        Preferences $preferences,
        Notifications $notifications,
        Avatar $avatar
    ) {
        $this->setUserId($userId);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setName($name);
        $this->setPreferences($preferences);
        $this->setNotifications($notifications);
        $this->setAvatar($avatar);
    }

    private function setUserId(UserId $userId)
    {
        $this->userId = $userId;
    }

    private function setName(string $name)
    {
        $this->name = $name;
    }

    private function setEmail(string $email)
    {
        $this->email = $email;
    }

    private function setAvatar(Avatar $avatar)
    {
        $this->avatar = $avatar;
    }

    private function setPreferences(Preferences $preferences)
    {
        $this->preferences = $preferences;
    }

    private function setNotifications(Notifications $notifications)
    {
        $this->notifications = $notifications;
    }

    private function setPassword(string $password)
    {
        $this->password = $password;
    }
}
