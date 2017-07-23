<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Event\EventComment;
use Teamo\Project\Domain\Model\Project\Event\EventCommentRepository;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Tests\TestCase;

class DoctrineEventCommentRepositoryTest extends TestCase
{
    /** @var EventCommentRepository */
    private $commentRepository;

    /** @var EntityManagerInterface */
    private $em;

    public function setUp()
    {
        parent::setUp();

        $this->em = app(EntityManagerInterface::class);
        $this->commentRepository = $this->em->getRepository(EventComment::class);
    }

    public function testRepositoryCanAddAndRemoveComment()
    {
        $commentId = new CommentId(uniqid('unit_test_'));
        $eventId = new EventId(uniqid('unit_test_'));
        $teamMemberId = new TeamMemberId(uniqid('unit_test_'));
        $attachments = new Collection([new Attachment('a-1', 'attachment.txt')]);

        $comment = new EventComment($eventId, $commentId, $teamMemberId, 'Content', $attachments);
        $this->commentRepository->add($comment);
        $this->em->flush();

        $savedComment = $this->commentRepository->ofId($commentId, $eventId);
        $this->assertEquals('Content', $savedComment->content());
        $this->assertEquals($teamMemberId, $savedComment->author());
        $this->assertEquals('attachment.txt', $savedComment->attachments()['a-1']->name());
        $this->assertTrue($savedComment->attachments()['a-1']->type()->isFile());

        $this->commentRepository->remove($savedComment);
        $this->em->flush();

        $this->expectException(\InvalidArgumentException::class);
        $this->commentRepository->ofId($commentId, $eventId);
    }

    public function testRepositoryReturnsOnlyCommentsOfRequestedEvent()
    {
        $commentId1 = new CommentId(uniqid('unit_test_'));
        $commentId2 = new CommentId(uniqid('unit_test_'));

        $eventId1 = new EventId(uniqid('unit_test_'));
        $eventId2 = new EventId(uniqid('unit_test_'));

        $teamMemberId = new TeamMemberId(uniqid('unit_test_'));
        $attachments = new Collection();

        $comment1 = new EventComment($eventId1, $commentId1, $teamMemberId, 'Content 1', $attachments);
        $comment2 = new EventComment($eventId2, $commentId2, $teamMemberId, 'Content 2', $attachments);
        $this->commentRepository->add($comment1);
        $this->commentRepository->add($comment2);
        $this->em->flush();

        $commentsOfEvent1 = $this->commentRepository->all($eventId1);
        $this->assertCount(1, $commentsOfEvent1);
        $this->assertEquals($commentId1, $commentsOfEvent1[0]->commentId());

        $commentsOfEvent2 = $this->commentRepository->all($eventId2);
        $this->assertCount(1, $commentsOfEvent2);
        $this->assertEquals($commentId2, $commentsOfEvent2[0]->commentId());

        $this->commentRepository->remove($comment1);
        $this->commentRepository->remove($comment2);
        $this->em->flush();
    }
}
