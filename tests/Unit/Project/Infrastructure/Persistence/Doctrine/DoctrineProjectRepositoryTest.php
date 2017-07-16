<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMember;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Teamo\Project\Domain\Model\Team\TeamMemberRepository;
use Tests\TestCase;

class DoctrineProjectRepositoryTest extends TestCase
{
    /** @var ProjectRepository */
    private $projectRepository;

    /** @var TeamMemberRepository */
    private $teamMemberRepository;

    /** @var EntityManagerInterface */
    private $em;

    public function setUp()
    {
        parent::setUp();

        $this->em = app(EntityManagerInterface::class);
        $this->projectRepository = $this->em->getRepository(Project::class);
        $this->teamMemberRepository = $this->em->getRepository(TeamMember::class);
    }

    public function testRepositoryCanAddAndRemoveProject()
    {
        $teamMemberId = new TeamMemberId(uniqid('unit_test_'));
        $projectId = new ProjectId(uniqid('unit_test_'));

        $teamMember = new TeamMember($teamMemberId, 'Jane Doe');
        $this->teamMemberRepository->add($teamMember);
        $this->em->flush();

        $project = Project::start($teamMember, $projectId, 'My Project');
        $this->projectRepository->add($project);
        $this->em->flush();

        $savedProject = $this->projectRepository->ofId($projectId, $teamMember->teamMemberId());
        $this->assertEquals('My Project', $savedProject->name());
        $this->assertEquals($teamMemberId, $savedProject->owner());

        $this->projectRepository->remove($savedProject);
        $this->em->flush();
        $this->teamMemberRepository->remove($teamMember);
        $this->em->flush();

        $this->expectException(\InvalidArgumentException::class);
        $this->projectRepository->ofId($projectId, $teamMemberId);
    }

    public function testRepositoryReturnsOnlyOwnedProjects()
    {
        $teamMemberId1 = new TeamMemberId(uniqid('unit_test_'));
        $teamMemberId2 = new TeamMemberId(uniqid('unit_test_'));
        $teamMember1 = new TeamMember($teamMemberId1, 'Team Member 1');
        $teamMember2 = new TeamMember($teamMemberId2, 'Team Member 2');

        $this->teamMemberRepository->add($teamMember1);
        $this->teamMemberRepository->add($teamMember2);
        $this->em->flush();

        $projectId1 = new ProjectId(uniqid('unit_test_'));
        $projectId2 = new ProjectId(uniqid('unit_test_'));
        $projectId3 = new ProjectId(uniqid('unit_test_'));

        $project1 = Project::start($teamMember1, $projectId1, 'Project 1');
        $project2 = Project::start($teamMember1, $projectId2, 'Project 2');
        $project3 = Project::start($teamMember2, $projectId3, 'Project 3');

        $this->projectRepository->add($project1);
        $this->projectRepository->add($project2);
        $this->projectRepository->add($project3);
        $this->em->flush();

        $ownedProjects = $this->projectRepository->all($teamMemberId1);
        $this->assertCount(2, $ownedProjects);

        $this->expectException(\InvalidArgumentException::class);
        try {
            $this->projectRepository->ofId($projectId3, $teamMemberId1);
        } catch (\Exception $e) {
            $this->projectRepository->remove($project1);
            $this->projectRepository->remove($project2);
            $this->projectRepository->remove($project3);
            $this->em->flush();
            $this->teamMemberRepository->remove($teamMember1);
            $this->teamMemberRepository->remove($teamMember2);
            $this->em->flush();
            throw $e;
        }
    }
}
