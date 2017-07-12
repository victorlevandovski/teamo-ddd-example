<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\Comment;

use Illuminate\Support\Collection;
use Teamo\Common\Domain\Entity;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Project\Attachment\Attachments;
use Teamo\Project\Domain\Model\Team\TeamMemberId;

abstract class Comment extends Entity
{
    use Attachments;

    protected $commentId;
    protected $authorId;
    protected $content;

    public function __construct(CommentId $commentId, TeamMemberId $authorId, string $content, Collection $attachments)
    {
        $this->setCommentId($commentId);
        $this->setAuthorId($authorId);
        $this->setContentAndAttachments($content, $attachments);
    }

    public function commentId(): CommentId
    {
        return $this->commentId;
    }

    public function author(): TeamMemberId
    {
        return $this->authorId;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function update(string $content)
    {
        $this->setContent($content);
    }

    protected function setCommentId(CommentId $commentId)
    {
        $this->commentId = $commentId;
    }

    public function setAuthorId(TeamMemberId $authorId)
    {
        $this->authorId = $authorId;
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
}
