<?php
declare(strict_types=1);

namespace Teamo\Project\Application\Query\Discussion;

use Teamo\Project\Domain\Model\Project\Discussion\DiscussionCommentRepository;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class DiscussionQueryHandler
{
    private $projectRepository;
    private $discussionRepository;
    private $commentRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        DiscussionRepository $discussionRepository,
        DiscussionCommentRepository $commentRepository
    ) {
       $this->projectRepository = $projectRepository;
       $this->discussionRepository = $discussionRepository;
       $this->commentRepository = $commentRepository;
    }

    public function discussion(DiscussionQuery $query): DiscussionPayload
    {
        $projectId = new ProjectId($query->projectId());
        $discussionId = new DiscussionId($query->discussionId());

        return new DiscussionPayload(
            $this->projectRepository->ofId($projectId, new TeamMemberId($query->teamMemberId())),
            $this->discussionRepository->ofId($discussionId, $projectId),
            $this->commentRepository->all($discussionId)
        );
    }

    public function allDiscussions(AllDiscussionsQuery $query): DiscussionsPayload
    {
        $projectId = new ProjectId($query->projectId());

        if (!$query->archived()) {
            $discussions = $this->discussionRepository->allActive($projectId);
        } else {
            $discussions = $this->discussionRepository->allArchived($projectId);
        }

        return new DiscussionsPayload(
            $this->projectRepository->ofId($projectId, new TeamMemberId($query->teamMemberId())),
            $discussions
        );
    }
}
