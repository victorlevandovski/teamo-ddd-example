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
        //$this->project = new Project(new OwnerId('id-1'), new ProjectId('id-1'), 'My Project');
    }

    public function testConstructedUserIsValid()
    {
        $user = new User(new UserId('user-1'), 'John Doe', 'user1@example.com', 'p4ssw0rd', 'Europe/Amsterdam');

        $this->assertSame('user-1', $user->userId()->id());
        $this->assertEquals('John Doe', $user->name());
        $this->assertEquals('user1@example.com', $user->email());
        $this->assertEquals('p4ssw0rd', $user->password());
        $this->assertEquals('Europe/Amsterdam', $user->preferences()->timezone());
        $this->assertTrue($user->preferences()->notifications()->whenDiscussionStarted());
    }

    public function testUserCanBeUpdated()
    {
        $user = new User(new UserId('user-1'), 'John Doe', 'user1@example.com', 'p4ssw0rd', 'Europe/Amsterdam');

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

        $preferences = new Preferences('ru', 'America/New_York', 12, 'mm/dd/yyyy', 7, 'todos', $user->preferences()->notifications());
        $user->updatePreferences($preferences);
        $this->assertEquals('ru', $user->preferences()->language());
        $this->assertFalse($user->preferences()->notifications()->whenDiscussionStarted());
    }
}
