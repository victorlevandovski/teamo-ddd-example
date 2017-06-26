<?php

namespace Teamo\Project\Domain\Model\Project\Comment;

use Illuminate\Support\Collection;
use Teamo\Common\Domain\Entity;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Project\Attachment\Attachments;

abstract class Comment extends Entity
{
    use Attachments;

    protected $commentId;
    protected $author;
    protected $content;

    public function __construct(CommentId $commentId, Author $author, $content, Collection $attachments = null)
    {
        $this->setCommentId($commentId);
        $this->setAuthor($author);
        $this->setContentAndAttachments($content, $attachments ?: new Collection());
    }

    protected function setCommentId(CommentId $commentId)
    {
        $this->commentId = $commentId;
    }

    public function setAuthor(Author $author)
    {
        $this->author = $author;
    }

    protected function setContent($content)
    {
        $this->content = $content;
    }

    protected function setContentAndAttachments($content, Collection $attachments)
    {
        if ($attachments->isEmpty()) {
            $this->assertArgumentNotEmpty($content, 'Content cannot be empty');
        }

        $this->setContent($content);
        $this->setAttachments($attachments);
    }

    public function commentId()
    {
        return $this->commentId;
    }

    public function author()
    {
        return $this->author;
    }

    public function content()
    {
        return $this->content;
    }

    public function update($newContent)
    {
        $this->setContent($newContent);
    }
}
