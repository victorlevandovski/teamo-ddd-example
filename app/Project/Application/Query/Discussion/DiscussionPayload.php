<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Query\Discussion;

use Illuminate\Support\Collection;
use Teamo\Project\Application\Query\ProjectPayload;
use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionComment;
use Teamo\Project\Domain\Model\Project\Project;

class DiscussionPayload extends ProjectPayload
{
    private $discussion;
    private $comments;

    public function __construct(Project $project, Discussion $discussion, Collection $comments)
    {
        parent::__construct($project);

        $this->discussion = $discussion;
        $this->comments = $comments;
    }

    public function discussion(): Discussion
    {
        return $this->discussion;
    }

    /** @return Collection|DiscussionComment[] */
    public function comments(): Collection
    {
        return $this->comments;
    }
}
