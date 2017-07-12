<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityRepository;
use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class DoctrineProjectRepository extends EntityRepository implements ProjectRepository
{
    public function add(Project $project)
    {
        $this->getEntityManager()->persist($project);
    }

    public function remove(Project $project)
    {
        $this->getEntityManager()->remove($project);
    }

    public function ofId(TeamMemberId $teamMemberId, ProjectId $projectId): Project
    {
        $project = $this->findOneBy(['projectId.id' => $projectId->id(), 'ownerId.id' => $teamMemberId->id()]);

        if (null === $project) {
            throw new \InvalidArgumentException('Invalid project id');
        }

        return $project;
    }

    public function allOwnedBy(TeamMemberId $ownerId): Collection
    {
        return new Collection($this->findBy(['ownerId.id' => $ownerId->id()]));
    }
}
