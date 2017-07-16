<?php
declare(strict_types=1);

namespace Tests\Unit\User\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Teamo\Common\Facade\DomainEventPublisher;
use Teamo\User\Domain\Model\User\User;
use Teamo\User\Domain\Model\User\UserId;
use Teamo\User\Domain\Model\User\UserRepository;
use Tests\TestCase;

class DoctrineUserRepositoryTest extends TestCase
{
    /** @var UserRepository */
    private $repository;

    /** @var EntityManagerInterface */
    private $em;

    public function setUp()
    {
        parent::setUp();

        DomainEventPublisher::shouldReceive('publish');

        $this->em = app(EntityManagerInterface::class);
        $this->repository = $this->em->getRepository(User::class);
    }

    public function testRepositoryCanAddAndRemoveUser()
    {
        $userId = new UserId('user-1');
        $user = User::register($userId, 'john.doe@example.com', 'p4ssw0rd', 'John Doe', 'UTC');

        $this->repository->add($user);
        $this->em->flush();

        $savedUser = $this->repository->ofId($userId);
        $this->assertEquals($userId->id(), $userId->id());
        $this->assertEquals('john.doe@example.com', $savedUser->email());

        $this->repository->remove($savedUser);
        $this->em->flush();

        $this->expectException(\InvalidArgumentException::class);
        $this->repository->ofId($userId);
    }
}
