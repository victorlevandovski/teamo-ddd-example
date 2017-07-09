<?php
declare(strict_types=1);

namespace Tests\Unit\User\Application\Command;

use Teamo\User\Application\Command\User\ChangeUserEmailCommand;
use Teamo\User\Application\Command\User\ChangeUserEmailHandler;
use Teamo\User\Application\Command\User\ChangeUserPasswordCommand;
use Teamo\User\Application\Command\User\ChangeUserPasswordHandler;
use Teamo\User\Application\Command\User\RegisterUserCommand;
use Teamo\User\Application\Command\User\RegisterUserHandler;
use Teamo\User\Application\Command\User\RemoveUserAvatarCommand;
use Teamo\User\Application\Command\User\RemoveUserAvatarHandler;
use Teamo\User\Application\Command\User\UpdateUserAvatarCommand;
use Teamo\User\Application\Command\User\UpdateUserAvatarHandler;
use Teamo\User\Application\Command\User\UpdateUserNotificationSettingsCommand;
use Teamo\User\Application\Command\User\UpdateUserNotificationSettingsHandler;
use Teamo\User\Application\Command\User\UpdateUserProfileCommand;
use Teamo\User\Application\Command\User\UpdateUserProfileHandler;
use Teamo\User\Domain\Model\User\User;
use Teamo\User\Domain\Model\User\UserId;
use Teamo\User\Infrastructure\Persistence\InMemory\InMemoryUserRepository;
use Tests\TestCase;

class UserHandlersTest extends TestCase
{
    /** @var InMemoryUserRepository */
    private $userRepository;

    /** @var  User */
    private $user;

    public function setUp()
    {
        $this->userRepository = new InMemoryUserRepository();

        $this->user = User::register(new UserId('test-user'), 'john.doe@example.com', 'p4ssw0rd', 'John Doe', 'Europe/Amsterdam');
        $this->userRepository->add($this->user);
    }

    public function testRegisterUserHandlerAddsUserToRepository()
    {
        $command = new RegisterUserCommand('user-1', 'john.doe@example.com', 'p4ssw0rd', 'John Doe', 'Europe/Amsterdam');
        $handler = new RegisterUserHandler($this->userRepository);
        $handler->handle($command);

        $user = $this->userRepository->ofId(new UserId('user-1'));

        $this->assertSame('user-1', $user->userId()->id());
        $this->assertEquals('john.doe@example.com', $user->email());
        $this->assertEquals('p4ssw0rd', $user->password());
        $this->assertEquals('John Doe', $user->name());
        $this->assertEquals('Europe/Amsterdam', $user->preferences()->timezone());
    }

    public function testUpdateUserProfileHandlerUpdatesUserNameAndPreferences()
    {
        $user = $this->user;

        $command = new UpdateUserProfileCommand($user->userId()->id(), 'Jake Doe', 'America/New_York', 'mm/dd/yyyy', 12, 7, 'us');
        $handler = new UpdateUserProfileHandler($this->userRepository);
        $handler->handle($command);

        $this->assertEquals('Jake Doe', $user->name());
        $this->assertEquals('America/New_York', $user->preferences()->timezone());
        $this->assertEquals('mm/dd/yyyy', $user->preferences()->dateFormat());
        $this->assertEquals(12, $user->preferences()->timeFormat());
        $this->assertEquals(7, $user->preferences()->firstDayOfWeek());
        $this->assertEquals('us', $user->preferences()->language());
    }

    public function testChangeUserEmailHandlerUpdatesEmail()
    {
        $user = $this->user;

        $command = new ChangeUserEmailCommand($user->userId()->id(), 'new.email@example.com', 'p4ssw0rd');
        $handler = new ChangeUserEmailHandler($this->userRepository);
        $handler->handle($command);

        $this->assertEquals('new.email@example.com', $user->email());
    }

    public function testChangeUserPasswordHandlerUpdatesPassword()
    {
        $user = $this->user;

        $command = new ChangeUserPasswordCommand($user->userId()->id(), 'newP4ssw0rd', 'p4ssw0rd');
        $handler = new ChangeUserPasswordHandler($this->userRepository);
        $handler->handle($command);

        $this->assertEquals('newP4ssw0rd', $user->password());
    }

    public function testRemoveUserAvatarHandlerRemovesAvatar()
    {
        $user = $this->user;

        $command = new RemoveUserAvatarCommand($user->userId()->id());
        $handler = new RemoveUserAvatarHandler($this->userRepository);
        $handler->handle($command);

        $this->assertEquals('default', $user->avatar()->path());
    }

    public function testUpdateUserAvatarHandlerUpdatesAvatar()
    {
        $user = $this->user;

        $command = new UpdateUserAvatarCommand($user->userId()->id(), 'avatar.jpg');
        $handler = new UpdateUserAvatarHandler($this->userRepository);
        $handler->handle($command);

        $this->assertEquals('avatar.jpg', $user->avatar()->path());
    }

    public function testUpdateUserNotificationSettingsUpdatesNotifications()
    {
        $user = $this->user;

        $command = new UpdateUserNotificationSettingsCommand($user->userId()->id(), false, false, false, false, false, false, false);
        $handler = new UpdateUserNotificationSettingsHandler($this->userRepository);
        $handler->handle($command);

        $this->assertFalse($user->notifications()->whenDiscussionStarted());
        $this->assertFalse($user->notifications()->whenDiscussionCommented());
        $this->assertFalse($user->notifications()->whenTodoListCreated());
        $this->assertFalse($user->notifications()->whenTodoCommented());
        $this->assertFalse($user->notifications()->whenTodoAssignedToMe());
        $this->assertFalse($user->notifications()->whenEventAdded());
        $this->assertFalse($user->notifications()->whenEventCommented());
    }
}
