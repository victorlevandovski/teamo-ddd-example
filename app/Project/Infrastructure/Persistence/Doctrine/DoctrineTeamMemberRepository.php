<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\Doctrine;

use Teamo\Common\Infrastructure\Persistence\DoctrineRepository;
use Teamo\Project\Domain\Model\Team\TeamMember;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Teamo\Project\Domain\Model\Team\TeamMemberRepository;

class DoctrineTeamMemberRepository extends DoctrineRepository implements TeamMemberRepository
{
    public function add(TeamMember $teamMember)
    {
        $this->getEntityManager()->persist($teamMember);
    }

    public function remove(TeamMember $teamMember)
    {
        $this->getEntityManager()->remove($teamMember);
    }

    public function ofId(TeamMemberId $teamMemberId): TeamMember
    {
        return $this->find($teamMemberId);
    }
}
