<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Command\Project;

use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Teamo\Project\Domain\Model\Team\TeamMemberRepository;

class StartNewProjectHandler extends ProjectHandler
{
    private $teamMemberRepository;

    public function __construct(ProjectRepository $projectRepository, TeamMemberRepository $teamMemberRepository)
    {
        parent::__construct($projectRepository);

        $this->teamMemberRepository = $teamMemberRepository;
    }

    public function handle(StartNewProjectCommand $command)
    {
        $teamMember = $this->teamMemberRepository->ofId(new TeamMemberId($command->owner()));

        $project = Project::start($teamMember, new ProjectId($command->projectId()), $command->name());

        $this->projectRepository->add($project);
    }
}
