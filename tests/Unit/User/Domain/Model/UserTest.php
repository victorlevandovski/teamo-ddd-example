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
        $this->assertEquals('p4ssw0rd', $user->password());
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

        $preferences = new Preferences('us', 'America/New_York', 'mm/dd/yyyy', 12, 7, 'todos');
        $user->updatePreferences($preferences);
        $this->assertEquals('us', $user->preferences()->language());
        $this->assertFalse($user->notifications()->whenDiscussionStarted());

        $user->updateAvatar(new Avatar('user_avatar.jpg'));
        $this->assertEquals('user_avatar.jpg', $user->avatar()->path());

        $user->removeAvatar();
        $this->assertEquals('default', $user->avatar()->path());
    }

    public function testUserCanChangeEmail()
    {
        $user = User::register(new UserId('1'), 'user1@example.com', 'p4ssw0rd', 'John Doe', 'UTC');

        $user->changeEmail('jack.doe@example.com', 'p4ssw0rd');
        $this->assertEquals('jack.doe@example.com', $user->email());

        $this->expectException(\InvalidArgumentException::class);
        $user->changeEmail('jack.doe@example.com', 'wrong_password');
    }

    public function testUserCanChangePassword()
    {
        $user = User::register(new UserId('1'), 'user1@example.com', 'p4ssw0rd', 'John Doe', 'UTC');

        $user->changePassword('pa55word', 'p4ssw0rd');
        $this->assertEquals('pa55word', $user->password());

        $this->expectException(\InvalidArgumentException::class);
        $user->changePassword('pa55w0rd', 'wrong_password');
    }
}
