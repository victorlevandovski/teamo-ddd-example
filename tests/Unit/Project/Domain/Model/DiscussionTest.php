<?php

namespace Tests\Unit\Project\Domain\Model\Project;

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
