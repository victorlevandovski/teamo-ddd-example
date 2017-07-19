<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Discussion;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\ProjectId;

interface DiscussionRepository
{
    public function add(Discussion $discussion);

    public function remove(Discussion $discussion);

    public function ofId(DiscussionId $discussionId, ProjectId $projectId): Discussion;

    /**
     * @param ProjectId $projectId
     * @return Collection|Discussion[]
     */
    public function allActive(ProjectId $projectId): Collection;

    /**
     * @param ProjectId $projectId
     * @return Collection|Discussion[]
     */
    public function allArchived(ProjectId $projectId): Collection;
}
