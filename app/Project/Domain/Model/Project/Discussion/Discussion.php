<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Discussion;

use Illuminate\Support\Collection;
use Teamo\Common\Domain\CreatedOn;
use Teamo\Common\Domain\Entity;
use Teamo\Project\Domain\Model\Project\Attachment\Attachments;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

class Discussion extends Entity
{
    use CreatedOn;
    use Attachments {
        removeAttachment as removeDiscussionAttachment;
    }

    private $projectId;
    private $discussionId;
    private $author;
    private $topic;
    private $content;
    private $archived;

    public function __construct(ProjectId $projectId, DiscussionId $discussionId, TeamMemberId $author, string $topic, string $content, Collection $attachments)
    {
        $this->setProjectId($projectId);
        $this->setDiscussionId($discussionId);
        $this->setAuthor($author);
        $this->setTopic($topic);
        $this->setContent($content);
        $this->setArchived(false);
        $this->setAttachments($attachments);
        $this->resetCreatedOn();
    }

    public function projectId(): ProjectId
    {
        return $this->projectId;
    }

    public function discussionId(): DiscussionId
    {
        return $this->discussionId;
    }

    public function author(): TeamMemberId
    {
        return $this->author;
    }

    public function topic(): string
    {
        return $this->topic;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function update(string $topic, string $content)
    {
        $this->setTopic($topic);
        $this->setContent($content);
    }

    public function archive()
    {
        $this->archived = true;
    }

    public function restore()
    {
        $this->archived = false;
    }

    public function comment(CommentId $commentId, TeamMemberId $author, string $content, Collection $attachments)
    {
        return new DiscussionComment($this->discussionId(), $commentId, $author, $content, $attachments);
    }

    public function removeAttachment(string $id, TeamMemberId $author)
    {
        $this->removeDiscussionAttachment($id, $this->author());
    }

    private function setProjectId(ProjectId $projectId)
    {
        $this->projectId = $projectId;
    }

    private function setDiscussionId(DiscussionId $discussionId)
    {
        $this->discussionId = $discussionId;
    }

    private function setAuthor(TeamMemberId $author)
    {
        $this->author = $author;
    }

    private function setTopic(string $topic)
    {
        $this->topic = $topic;
    }

    private function setContent(string $content)
    {
        $this->content = $content;
    }

    private function setArchived(bool $archived)
    {
        $this->archived = $archived;
    }

    protected function assertIsAuthor(TeamMemberId $author)
    {
        if (!$this->author()->equals($author)) {
            throw new \InvalidArgumentException('Provided team member is not an author of this comment');
        }
    }
}
