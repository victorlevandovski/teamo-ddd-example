<?php
declare(strict_types=1);

namespace Tests\Unit\User\Domain\Model;

use Teamo\User\Domain\Model\User\Avatar;
use Teamo\User\Domain\Model\User\Notifications;
use Teamo\User\Domain\Model\User\Preferences;
use Teamo\User\Domain\Model\User\User;
use Teamo\User\Domain\Model\User\UserId;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function setUp()
    {
        //
    }

    public function testUserCanBeRegistered()
    {
        $user = User::register(new UserId('1'), 'user1@example.com', 'p4ssw0rd', 'John Doe', 'Europe/Amsterdam');

        $this->assertNotNull($user->userId());
        $this->assertEquals('John Doe', $user->name());
        $this->assertEquals('user1@example.com', $user->email());
        $this->assertEquals('Europe/Amsterdam', $user->preferences()->timezone());
        $this->assertTrue($user->notifications()->whenDiscussionStarted());
    }

    public function testUserCanBeUpdated()
    {
        $user = User::register(new UserId('1'), 'user1@example.com', 'p4ssw0rd', 'John Doe', 'Europe/Amsterdam');

        $user->rename('Jack Doe');
        $this->assertEquals('Jack Doe', $user->name());

        $this->assertTrue($user->notifications()->whenDiscussionStarted());
        $notifications = new Notifications(false, false, false, false, false, false, false);
        $user->updateNotificationSettings($notifications);
        $this->assertFalse($user->notifications()->whenDiscussionStarted());

        $preferences = new Preferences('ru', 'America/New_York', 'mm/dd/yyyy', 12, 7, 'todos');
        $user->updatePreferences($preferences);
        $this->assertEquals('ru', $user->preferences()->language());
        $this->assertFalse($user->notifications()->whenDiscussionStarted());

        $user->updateAvatar(new Avatar('avatar48.jpg', 'avatar96.jpg'));
        $this->assertEquals('avatar48.jpg', $user->avatar()->pathTo48pxAvatar());
        $this->assertEquals('avatar96.jpg', $user->avatar()->pathTo96pxAvatar());

        $user->removeAvatar();
        $this->assertEquals('/avatars/avatar48.jpg', $user->avatar()->pathTo48pxAvatar());
        $this->assertEquals('/avatars/avatar96.jpg', $user->avatar()->pathTo96pxAvatar());
    }

    public function testUserCanChangeEmail()
    {
        $user = User::register(new UserId('1'), 'user1@example.com', 'p4ssw0rd', 'John Doe', 'UTC');

        $user->changeEmail('jack.doe@example.com');
        $this->assertEquals('jack.doe@example.com', $user->email());
    }

    public function testUserCanChangePassword()
    {
        $user = User::register(new UserId('1'), 'user1@example.com', 'p4ssw0rd', 'John Doe', 'UTC');

        $user->changePassword('pa55word');
        $this->assertEquals('pa55word', $user->password());
    }
}
