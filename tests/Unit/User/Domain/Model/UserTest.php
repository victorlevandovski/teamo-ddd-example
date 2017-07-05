<?php
declare(strict_types=1);

namespace Tests\Unit\User\Domain\Model;

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
        $user = User::register('user1@example.com', 'p4ssw0rd', 'John Doe', 'Europe/Amsterdam');

        $this->assertNotNull($user->userId());
        $this->assertEquals('John Doe', $user->name());
        $this->assertEquals('user1@example.com', $user->email());
        $this->assertEquals('p4ssw0rd', $user->password());
        $this->assertEquals('Europe/Amsterdam', $user->preferences()->timezone());
        $this->assertTrue($user->preferences()->notifications()->whenDiscussionStarted());
    }

    public function testUserCanBeUpdated()
    {
        $user = User::register('user1@example.com', 'p4ssw0rd', 'John Doe', 'Europe/Amsterdam');

        $user->rename('Jack Doe');
        $this->assertEquals('Jack Doe', $user->name());

        $user->changeEmail('jack.doe@example.com');
        $this->assertEquals('jack.doe@example.com', $user->email());

        $user->changePassword('pa55word');
        $this->assertEquals('pa55word', $user->password());

        $this->assertTrue($user->preferences()->notifications()->whenDiscussionStarted());
        $notifications = new Notifications(false, false, false, false, false, false, false);
        $preferences = $user->preferences()->updateNotifications($notifications);
        $user->updatePreferences($preferences);
        $this->assertFalse($user->preferences()->notifications()->whenDiscussionStarted());

        $preferences = new Preferences('us', 'America/New_York', 'mm/dd/yyyy', 12, 7, 'todos', $user->preferences()->notifications());
        $user->updatePreferences($preferences);
        $this->assertEquals('us', $user->preferences()->language());
        $this->assertFalse($user->preferences()->notifications()->whenDiscussionStarted());
    }
}
