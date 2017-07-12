<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Tests\TestCase;

class DoctrineProjectRepositoryTest extends TestCase
{
    /** @var ProjectRepository */
    private $repository;

    /** @var EntityManagerInterface */
    private $em;

    public function setUp()
    {
        parent::setUp();

        $this->em = app(EntityManagerInterface::class);
        $this->repository = $this->em->getRepository(Project::class);
    }

    public function testRepositoryCanAddAndRemoveProject()
    {
        $projectId = new ProjectId('project-1');
        $ownerId = new TeamMemberId('member-1');
        $project = Project::start($ownerId, $projectId, 'My Project');

        $this->repository->add($project);
        $this->em->flush();

        $savedProject = $this->repository->ofId($ownerId, $projectId);
        $this->assertEquals('My Project', $savedProject->name());

        $this->repository->remove($savedProject);
        $this->em->flush();

        $this->expectException(\InvalidArgumentException::class);
        $this->repository->ofId($ownerId, $projectId);
    }

    public function testRepositoryReturnsOnlyOwnedProjects()
    {
        $ownerId1 = new TeamMemberId(Uuid::uuid4()->toString());
        $ownerId2 = new TeamMemberId(Uuid::uuid4()->toString());

        $projectId1 = new ProjectId(Uuid::uuid4()->toString());
        $projectId2 = new ProjectId(Uuid::uuid4()->toString());
        $projectId3 = new ProjectId(Uuid::uuid4()->toString());

        $project1 = Project::start($ownerId1, $projectId1, 'Project 1');
        $project2 = Project::start($ownerId1, $projectId2, 'Project 2');
        $project3 = Project::start($ownerId2, $projectId3, 'Project 3');

        $this->repository->add($project1);
        $this->repository->add($project2);
        $this->repository->add($project3);
        $this->em->flush();

        $ownedProjects = $this->repository->allOwnedBy($ownerId1);
        $this->assertCount(2, $ownedProjects);

        $this->repository->remove($project1);
        $this->repository->remove($project2);
        $this->repository->remove($project3);
        $this->em->flush();
    }
}
