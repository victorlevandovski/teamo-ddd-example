<?php

namespace Teamo\Project\Domain\Model\Project\Discussion;

use Illuminate\Support\Collection;
use Teamo\Common\Domain\Entity;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Collaborator\Author;

class Discussion extends Entity
{
    private $projectId;
    private $discussionId;
    private $author;
    private $topic;
    private $content;
    private $archived;

    public function __construct(ProjectId $projectId, DiscussionId $discussionId, Author $author, $topic, $content)
    {
        $this->setProjectId($projectId);
        $this->setDiscussionId($discussionId);
        $this->setAuthor($author);
        $this->setTopic($topic);
        $this->setContent($content);
        $this->setArchived(false);
    }

    private function setProjectId(ProjectId $projectId)
    {
        $this->projectId = $projectId;
    }

    private function setDiscussionId(DiscussionId $discussionId)
    {
        $this->discussionId = $discussionId;
    }

    private function setAuthor(Author $author)
    {
        $this->author = $author;
    }

    private function setTopic($topic)
    {
        $this->topic = $topic;
    }

    private function setContent($content)
    {
        $this->content = $content;
    }

    private function setArchived($archived)
    {
        $this->archived = $archived;
    }

    /**
     * @return ProjectId
     */
    public function projectId()
    {
        return $this->projectId;
    }

    /**
     * @return DiscussionId
     */
    public function discussionId()
    {
        return $this->discussionId;
    }

    /**
     * @return Author
     */
    public function author()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function topic()
    {
        return $this->topic;
    }

    /**
     * @return string
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * @return bool
     */
    public function isArchived()
    {
        return $this->archived;
    }

    public function update($newTopic, $newContent)
    {
        $this->setTopic($newTopic);
        $this->setContent($newContent);
    }

    public function archive()
    {
        $this->archived = true;
    }

    public function restore()
    {
        $this->archived = false;
    }

    public function comment(Author $author, $content, Collection $attachments = null)
    {
        return new DiscussionComment($this->discussionId(), new CommentId(), $author, $content, $attachments);
    }
}
