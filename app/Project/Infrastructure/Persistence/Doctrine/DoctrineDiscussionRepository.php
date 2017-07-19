<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\Doctrine;

use Illuminate\Support\Collection;
use Teamo\Common\Infrastructure\Persistence\DoctrineRepository;
use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;

class DoctrineDiscussionRepository extends DoctrineRepository implements DiscussionRepository
{
    public function add(Discussion $discussion)
    {
        $this->getEntityManager()->persist($discussion);
    }

    public function remove(Discussion $discussion)
    {
        $this->getEntityManager()->remove($discussion);
    }

    public function ofId(DiscussionId $discussionId, ProjectId $projectId): Discussion
    {
        $discussion = $this->findOneBy(['discussionId' => $discussionId, 'projectId' => $projectId]);

        if (null === $discussion) {
            throw new \InvalidArgumentException('Invalid discussion id or project id');
        }

        return $discussion;
    }

    public function allActive(ProjectId $projectId): Collection
    {
        return new Collection($this->findBy(['projectId' => $projectId, 'archived' => 0]));
    }

    public function allArchived(ProjectId $projectId): Collection
    {
        return new Collection($this->findBy(['projectId' => $projectId, 'archived' => 1]));
    }
}
