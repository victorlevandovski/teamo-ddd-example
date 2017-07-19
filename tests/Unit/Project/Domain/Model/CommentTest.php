<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Domain\Model\Project;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentId;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionComment;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /**
     * @var DiscussionComment
     */
    private $comment;

    public function setUp()
    {
        $this->comment = new DiscussionComment(new DiscussionId('1'), new CommentId('id-1'), new TeamMemberId('id-1'), 'Comment content', new Collection());
    }

    public function testCommentCanAddAndRemoveAttachment()
    {
        $attachment = new Attachment(new AttachmentId('1'), 'Image.jpg');
        $this->comment->attach($attachment);

        /** @var Attachment[] $attachments */
        $attachments = array_values($this->comment->attachments()->toArray());
        $this->assertInstanceOf(Attachment::class, $attachments[0]);

        $this->comment->removeAttachment($attachments[0]->attachmentId());
        $this->assertEmpty($this->comment->attachments());
    }

    public function testCommentShouldHaveContentOrAttachment()
    {
        $this->expectException(\InvalidArgumentException::class);

        new DiscussionComment(new DiscussionId('1'), new CommentId('1'), new TeamMemberId('id-1'), '', new Collection());
    }

    public function testCommentCanHaveEmptyContentIfAttachmentPresent()
    {
        $attachments = new Collection([new Attachment(new AttachmentId('1'), 'Image.png')]);

        new DiscussionComment(new DiscussionId('1'), new CommentId('1'), new TeamMemberId('id-1'), '', $attachments);
    }

    public function testCommentCanBeUpdated()
    {
        $this->comment->update('New content', new TeamMemberId('id-1'));
        $this->assertEquals('New content', $this->comment->content());

        $this->expectException(\InvalidArgumentException::class);
        $this->comment->update('Another content', new TeamMemberId('invalid-id-1'));
    }
}
