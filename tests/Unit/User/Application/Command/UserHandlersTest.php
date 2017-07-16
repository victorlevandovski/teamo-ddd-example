<?php
declare(strict_types=1);

namespace Tests\Unit\User\Application\Command;

use Illuminate\Contracts\Auth\Guard;
use Teamo\Common\Facade\DomainEventPublisher;
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
use Teamo\User\Domain\Model\User\Avatar;
use Teamo\User\Domain\Model\User\User;
use Teamo\User\Domain\Model\User\UserId;
use Teamo\User\Infrastructure\Persistence\InMemory\InMemoryUserRepository;
use Tests\TestCase;

class UserHandlersTest extends TestCase
{
    /** @var InMemoryUserRepository */
    private $userRepository;

    /** @var  Guard */
    private $guard;

    /** @var  User */
    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->userRepository = new InMemoryUserRepository();

        DomainEventPublisher::shouldReceive('publish');
        $this->guard = \Mockery::mock(Guard::class);
        $this->guard->shouldReceive('validate')->andReturn(true);

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
        $this->assertEquals('John Doe', $user->name());
        $this->assertEquals('Europe/Amsterdam', $user->preferences()->timezone());
    }

    public function testUpdateUserProfileHandlerUpdatesUserNameAndPreferences()
    {
        $command = new UpdateUserProfileCommand($this->user->userId()->id(), 'Jake Doe', 'America/New_York', 'mm/dd/yyyy', 12, 7, 'ru');
        $handler = new UpdateUserProfileHandler($this->userRepository);
        $handler->handle($command);

        $user = $this->userRepository->ofId($this->user->userId());

        $this->assertEquals('Jake Doe', $user->name());
        $this->assertEquals('America/New_York', $user->preferences()->timezone());
        $this->assertEquals('mm/dd/yyyy', $user->preferences()->dateFormat());
        $this->assertEquals(12, $user->preferences()->timeFormat());
        $this->assertEquals(7, $user->preferences()->firstDayOfWeek());
        $this->assertEquals('ru', $user->preferences()->language());
    }

    public function testChangeUserEmailHandlerUpdatesEmail()
    {
        $command = new ChangeUserEmailCommand($this->user->userId()->id(), 'new.email@example.com', 'p4ssw0rd');
        $handler = new ChangeUserEmailHandler($this->userRepository, $this->guard);
        $handler->handle($command);

        $user = $this->userRepository->ofId($this->user->userId());

        $this->assertEquals('new.email@example.com', $user->email());
    }

    public function testChangeUserPasswordHandlerUpdatesPassword()
    {
        $user = $this->user;

        $command = new ChangeUserPasswordCommand($user->userId()->id(), 'newP4ssw0rd', 'p4ssw0rd');
        $handler = new ChangeUserPasswordHandler($this->userRepository, $this->guard);
        $handler->handle($command);
    }

    public function testRemoveUserAvatarHandlerRemovesAvatar()
    {
        $this->user->updateAvatar(new Avatar('avatar48.jpg', 'avatar96.jpg'));

        $command = new RemoveUserAvatarCommand($this->user->userId()->id());
        $handler = new RemoveUserAvatarHandler($this->userRepository);
        $handler->handle($command);

        $user = $this->userRepository->ofId($this->user->userId());

        $this->assertEquals('/avatars/avatar48.jpg', $user->avatar()->pathTo48pxAvatar());
        $this->assertEquals('/avatars/avatar96.jpg', $user->avatar()->pathTo96pxAvatar());
    }

    public function testUpdateUserAvatarHandlerUpdatesAvatar()
    {
        $command = new UpdateUserAvatarCommand($this->user->userId()->id(), 'avatar48.jpg', 'avatar96.jpg');
        $handler = new UpdateUserAvatarHandler($this->userRepository);
        $handler->handle($command);

        $user = $this->userRepository->ofId($this->user->userId());

        $this->assertEquals('avatar48.jpg', $user->avatar()->pathTo48pxAvatar());
        $this->assertEquals('avatar96.jpg', $user->avatar()->pathTo96pxAvatar());
    }

    public function testUpdateUserNotificationSettingsUpdatesNotifications()
    {
        $command = new UpdateUserNotificationSettingsCommand($this->user->userId()->id(), false, false, false, false, false, false, false);
        $handler = new UpdateUserNotificationSettingsHandler($this->userRepository);
        $handler->handle($command);

        $user = $this->userRepository->ofId($this->user->userId());

        $this->assertFalse($user->notifications()->whenDiscussionStarted());
        $this->assertFalse($user->notifications()->whenDiscussionCommented());
        $this->assertFalse($user->notifications()->whenTodoListCreated());
        $this->assertFalse($user->notifications()->whenTodoCommented());
        $this->assertFalse($user->notifications()->whenTodoAssignedToMe());
        $this->assertFalse($user->notifications()->whenEventAdded());
        $this->assertFalse($user->notifications()->whenEventCommented());
    }
}
