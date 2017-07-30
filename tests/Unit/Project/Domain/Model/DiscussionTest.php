<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Domain\Model\Project;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionComment;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
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
            new TeamMemberId('id-1'),
            'My Topic',
            'My Content',
            new Collection()
        );
    }

    public function testDiscussionCanBeCommented()
    {
        $author = new TeamMemberId('id-1', 'John Doe');

        $comment = $this->discussion->comment(new CommentId('1'), $author, 'Comment content', new Collection());

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
