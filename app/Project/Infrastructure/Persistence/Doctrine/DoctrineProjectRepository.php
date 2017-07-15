<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\Doctrine;

use Illuminate\Support\Collection;
use Teamo\Common\Infrastructure\Persistence\DoctrineRepository;
use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class DoctrineProjectRepository extends DoctrineRepository implements ProjectRepository
{
    protected $castToArray = ['teamMembers'];

    public function add(Project $project)
    {
        $this->getEntityManager()->persist($project);
    }

    public function remove(Project $project)
    {
        $this->getEntityManager()->remove($project);
    }

    public function ofId(ProjectId $projectId, TeamMemberId $teamMemberId): Project
    {
        /** @var Project $project */
        $project = $this->find($projectId);

        if (null === $project) {
            throw new \InvalidArgumentException('Invalid project id');
        }

        $project = $this->processEntity($project);

        $teamMemberFound = false;
        foreach ($project->teamMembers() as $teamMember) {
            if ($teamMember->teamMemberId()->equals($teamMemberId)) {
                $teamMemberFound = true;
            }
        }
        if (!$teamMemberFound) {
            throw new \InvalidArgumentException('Requesting team member is not a member of requested project');
        }

        return $project;
    }

    public function allOfTeamMember(TeamMemberId $teamMemberId): Collection
    {
        $projects = $this->createQueryBuilder('p')
            ->join('p.teamMembers', 'tm')
            ->where('tm.teamMemberId = :teamMemberId')
            ->setParameter(0, $teamMemberId->id())
            ->getQuery()
            ->execute(['teamMemberId' => $teamMemberId]);

        return $this->processEntities($projects);
    }
}
