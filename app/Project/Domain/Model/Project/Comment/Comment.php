<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Comment;

use Illuminate\Support\Collection;
use Teamo\Common\Domain\CreatedOn;
use Teamo\Common\Domain\Entity;
use Teamo\Common\Domain\UpdatedOn;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Project\Attachment\Attachments;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

abstract class Comment extends Entity
{
    use CreatedOn, UpdatedOn;
    use Attachments;

    protected $commentId;
    protected $author;
    protected $content;

    public function __construct(CommentId $commentId, TeamMemberId $author, string $content, Collection $attachments)
    {
        $this->setCommentId($commentId);
        $this->setAuthor($author);
        $this->setContentAndAttachments($content, $attachments);
        $this->resetCreatedOn();
        $this->resetUpdatedOn();
    }

    public function commentId(): CommentId
    {
        return $this->commentId;
    }

    public function author(): TeamMemberId
    {
        return $this->author;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function update(string $content, TeamMemberId $author)
    {
        $this->assertIsAuthor($author);

        $this->setContent($content);
        $this->resetUpdatedOn();
    }

    public function assertCanUpdate(TeamMemberId $teamMemberId)
    {
        $this->assertIsAuthor($teamMemberId);
    }

    protected function setCommentId(CommentId $commentId)
    {
        $this->commentId = $commentId;
    }

    protected function setAuthor(TeamMemberId $author)
    {
        $this->author = $author;
    }

    protected function setContent(string $content)
    {
        $this->content = $content;
    }

    protected function setContentAndAttachments(string $content, Collection $attachments)
    {
        if ($attachments->isEmpty()) {
            $this->assertArgumentNotEmpty($content, 'Content cannot be empty');
        }

        $this->setContent($content);
        $this->setAttachments($attachments);
    }

    protected function assertIsAuthor(TeamMemberId $author)
    {
        if (!$this->author()->equals($author)) {
            throw new \InvalidArgumentException('Provided team member is not an author of this comment');
        }
    }
}
