<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\InMemory;

use Illuminate\Support\Collection;
use Teamo\Common\Infrastructure\Persistence\InMemory\InMemoryRepository;
use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;

class InMemoryDiscussionRepository extends InMemoryRepository implements DiscussionRepository
{
    public function add(Discussion $discussion)
    {
        $this->items->put($discussion->discussionId()->id(), $discussion);
    }

    public function remove(Discussion $discussion)
    {
        $this->items->forget($discussion->discussionId()->id());
    }

    public function ofId(DiscussionId $discussionId, ProjectId $projectId): Discussion
    {
        return $this->findOrFail($discussionId->id(), 'Invalid discussion id');
    }

    public function allActive(ProjectId $projectId): Collection
    {
        return $this->items->filter(function (Discussion $item) use ($projectId) {
            return $item->projectId()->equals($projectId) && !$item->isArchived();
        });
    }

    public function allArchived(ProjectId $projectId): Collection
    {
        return $this->items->filter(function (Discussion $item) use ($projectId) {
            return $item->projectId()->equals($projectId) && $item->isArchived();
        });
    }
}
