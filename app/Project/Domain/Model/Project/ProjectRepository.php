<?php

namespace Teamo\Project\Domain\Project;

use Teamo\Project\Domain\Model\Project\ProjectId;

interface ProjectRepository
{
    public function ofId(ProjectId $projectId);
}
