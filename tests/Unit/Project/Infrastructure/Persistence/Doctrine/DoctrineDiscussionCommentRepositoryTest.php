<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentId;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionComment;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionCommentRepository;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Tests\TestCase;

class DoctrineDiscussionCommentRepositoryTest extends TestCase
{
    /** @var DiscussionCommentRepository */
    private $commentRepository;

    /** @var EntityManagerInterface */
    private $em;

    public function setUp()
    {
        parent::setUp();

        $this->em = app(EntityManagerInterface::class);
        $this->commentRepository = $this->em->getRepository(DiscussionComment::class);
    }

    public function testRepositoryCanAddAndRemoveComment()
    {
        $commentId = new CommentId(uniqid('unit_test_'));
        $discussionId = new DiscussionId(uniqid('unit_test_'));
        $teamMemberId = new TeamMemberId(uniqid('unit_test_'));
        $attachments = new Collection([new Attachment(new AttachmentId('a-1'), 'attachment.txt')]);

        $comment = new DiscussionComment($discussionId, $commentId, $teamMemberId, 'Content', $attachments);
        $this->commentRepository->add($comment);
        $this->em->flush();

        $savedComment = $this->commentRepository->ofId($commentId, $discussionId);
        $this->assertEquals('Content', $savedComment->content());
        $this->assertEquals($teamMemberId, $savedComment->author());
        $this->assertEquals('attachment.txt', $savedComment->attachments()['a-1']->name());
        $this->assertTrue($savedComment->attachments()['a-1']->type()->isFile());

        $this->commentRepository->remove($savedComment);
        $this->em->flush();

        $this->expectException(\InvalidArgumentException::class);
        $this->commentRepository->ofId($commentId, $discussionId);
    }

    public function testRepositoryReturnsOnlyCommentsOfRequestedDiscussion()
    {
        $commentId1 = new CommentId(uniqid('unit_test_'));
        $commentId2 = new CommentId(uniqid('unit_test_'));

        $discussionId1 = new DiscussionId(uniqid('unit_test_'));
        $discussionId2 = new DiscussionId(uniqid('unit_test_'));

        $teamMemberId = new TeamMemberId(uniqid('unit_test_'));
        $attachments = new Collection();

        $comment1 = new DiscussionComment($discussionId1, $commentId1, $teamMemberId, 'Content 1', $attachments);
        $comment2 = new DiscussionComment($discussionId2, $commentId2, $teamMemberId, 'Content 2', $attachments);
        $this->commentRepository->add($comment1);
        $this->commentRepository->add($comment2);
        $this->em->flush();

        $commentsOfDiscussion1 = $this->commentRepository->all($discussionId1);
        $this->assertCount(1, $commentsOfDiscussion1);
        $this->assertEquals($commentId1, $commentsOfDiscussion1[0]->commentId());

        $commentsOfDiscussion2 = $this->commentRepository->all($discussionId2);
        $this->assertCount(1, $commentsOfDiscussion2);
        $this->assertEquals($commentId2, $commentsOfDiscussion2[0]->commentId());

        $this->commentRepository->remove($comment1);
        $this->commentRepository->remove($comment2);
        $this->em->flush();
    }
}
