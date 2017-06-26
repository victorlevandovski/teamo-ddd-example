<?php

namespace Tests\Unit\Project\Domain\Model\Project;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentId;
use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionComment;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Tests\TestCase;

class DiscussionTest extends TestCase
{
    /**
     * @var Discussion
     */
    private $discussion;

    public function setUp()
    {
        $this->discussion = new Discussion(
            new ProjectId('id-1'),
            new DiscussionId('id-1'),
            new Author('id-1', 'John Doe'),
            'My Topic',
            'My Content');
    }

    public function testConstructedDiscussionIsValid()
    {
        $projectId = new ProjectId('project-1');
        $discussionId = new DiscussionId('discussion-1');
        $author = new Author('author-1', 'John Doe');
        $attachments = new Collection(new Attachment(new AttachmentId('attachment-1'), 'Attachment.txt'));

        $discussion = new Discussion($projectId, $discussionId, $author, 'Topic', 'Content', $attachments);

        $this->assertSame($projectId, $discussion->projectId());
        $this->assertSame($discussionId, $discussion->discussionId());
        $this->assertSame($author, $discussion->author());
        $this->assertEquals('Topic', $discussion->topic());
        $this->assertEquals('Content', $discussion->content());
        $this->assertSame($attachments, $discussion->attachments());
    }

    public function testDiscussionCanBeCommented()
    {
        $author = new Author('id-1', 'John Doe');

        $comment = $this->discussion->comment($author, 'Comment content');

        $this->assertInstanceOf(DiscussionComment::class, $comment);
    }

    public function testDiscussionCanBeUpdated()
    {
        $this->assertEquals('My Topic', $this->discussion->topic());
        $this->assertEquals('My Content', $this->discussion->content());

        $this->discussion->update('New Topic', 'New Content');
        $this->assertEquals('New Topic', $this->discussion->topic());
        $this->assertEquals('New Content', $this->discussion->content());
    }

    public function testDiscussionCanBeArchivedAndRestored()
    {
        $this->assertFalse($this->discussion->isArchived());

        $this->discussion->archive();
        $this->assertTrue($this->discussion->isArchived());

        $this->discussion->restore();
        $this->assertFalse($this->discussion->isArchived());
    }
}