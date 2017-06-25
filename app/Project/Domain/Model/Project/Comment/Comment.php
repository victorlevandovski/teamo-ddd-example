<?php

namespace Teamo\Project\Domain\Model\Project\Comment;

use Illuminate\Support\Collection;
use Teamo\Common\Domain\Entity;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentId;

abstract class Comment extends Entity
{
    protected $commentId;
    protected $author;
    protected $content;

    /**
     * @var Collection
     */
    protected $attachments;

    public function __construct(CommentId $commentId, Author $author, $content, Collection $attachments = null)
    {
        $this->commentId = $commentId;
        $this->author = $author;
        $this->setContentAndAttachments($content, $attachments);
    }

    protected function setContent($content)
    {
        $this->assertArgumentNotEmpty($content, 'Content cannot be empty');

        $this->content = $content;
    }

    protected function setAttachments(Collection $attachments)
    {
        $this->attachments = $attachments;
    }

    protected function setContentAndAttachments($content, $attachments)
    {
        if (!$attachments) {
            $this->setContent($content);
            $this->attachments = new Collection();
        } else {
            $this->content = $content;
            $this->setAttachments($attachments);
        }
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

    public function attachments()
    {
        return $this->attachments;
    }

    public function update($newContent)
    {
        $this->setContent($newContent);
    }

    public function attach(Attachment $attachment)
    {
        $this->attachments[$attachment->attachmentId()->id()] = $attachment;
    }

    public function removeAttachment(AttachmentId $attachmentId)
    {
        if (isset($this->attachments[$attachmentId->id()])) {
            unset($this->attachments[$attachmentId->id()]);
        } else {
            throw new \InvalidArgumentException('Invalid attachment id');
        }
    }
}
