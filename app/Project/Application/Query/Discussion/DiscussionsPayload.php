<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Query\Discussion;

use Illuminate\Support\Collection;
use Teamo\Project\Application\Query\ProjectPayload;
use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Project;

class DiscussionsPayload extends ProjectPayload
{
    private $discussions;

    public function __construct(Project $project, Collection $discussions)
    {
        parent::__construct($project);

        $this->discussions = $discussions;
    }

    /** @return Collection|Discussion[] */
    public function discussions(): Collection
    {
        return $this->discussions;
    }
}
