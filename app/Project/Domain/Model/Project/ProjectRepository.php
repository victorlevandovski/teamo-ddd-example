<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project;

interface ProjectRepository
{
    public function ofId(ProjectId $projectId): Project;
}
