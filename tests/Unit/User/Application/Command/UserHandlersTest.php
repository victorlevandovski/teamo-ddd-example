<?php
declare(strict_types=1);

namespace Tests\Unit\User\Application\Command;

use Teamo\User\Application\Command\User\RegisterUserCommand;
use Teamo\User\Application\Command\User\RegisterUserHandler;
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

    public function setUp()
    {
        $this->userRepository = new InMemoryUserRepository();
    }

    public function testRegisterUserHandlerAddsUserToRepository()
    {
        $command = new RegisterUserCommand('john.doe@example.com', 'p4ssw0rd', 'John Doe', 'Europe/Amsterdam');
        $handler = new RegisterUserHandler($this->userRepository);
        $userId = $handler->handle($command);

        $user = $this->userRepository->ofId($userId);

        $this->assertSame($userId, $user->userId());
        $this->assertEquals('john.doe@example.com', $user->email());
        $this->assertEquals('p4ssw0rd', $user->password());
        $this->assertEquals('John Doe', $user->name());
        $this->assertEquals('Europe/Amsterdam', $user->preferences()->timezone());
    }

    public function testUpdateUserProfileHandlerUpdatesUserNameAndPreferences()
    {
        $user = User::register(new UserId('1'), 'john.doe@example.com', 'p4ssw0rd', 'John Doe', 'Europe/Amsterdam');
        $this->userRepository->add($user);

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
}
