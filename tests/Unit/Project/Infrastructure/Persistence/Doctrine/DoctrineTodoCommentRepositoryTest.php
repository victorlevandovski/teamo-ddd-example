<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoComment;
use Teamo\Project\Domain\Model\Project\TodoList\TodoCommentRepository;
use Teamo\Project\Domain\Model\Project\TodoList\TodoId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Tests\TestCase;

class DoctrineTodoCommentRepositoryTest extends TestCase
{
    /** @var TodoCommentRepository */
    private $commentRepository;

    /** @var EntityManagerInterface */
    private $em;

    public function setUp()
    {
        parent::setUp();

        $this->em = app(EntityManagerInterface::class);
        $this->commentRepository = $this->em->getRepository(TodoComment::class);
    }

    public function testRepositoryCanAddAndRemoveComment()
    {
        $commentId = new CommentId(uniqid('unit_test_'));
        $todoId = new TodoId(uniqid('unit_test_'));
        $teamMemberId = new TeamMemberId(uniqid('unit_test_'));
        $attachments = new Collection([new Attachment('a-1', 'attachment.txt')]);

        $comment = new TodoComment($todoId, $commentId, $teamMemberId, 'Content', $attachments);
        $this->commentRepository->add($comment);
        $this->em->flush();

        $savedComment = $this->commentRepository->ofId($commentId, $todoId);
        $this->assertEquals('Content', $savedComment->content());
        $this->assertEquals($teamMemberId, $savedComment->author());
        $this->assertEquals('attachment.txt', $savedComment->attachments()['a-1']->name());
        $this->assertTrue($savedComment->attachments()['a-1']->type()->isFile());

        $this->commentRepository->remove($savedComment);
        $this->em->flush();

        $this->expectException(\InvalidArgumentException::class);
        $this->commentRepository->ofId($commentId, $todoId);
    }

    public function testRepositoryReturnsOnlyCommentsOfRequestedTodo()
    {
        $commentId1 = new CommentId(uniqid('unit_test_'));
        $commentId2 = new CommentId(uniqid('unit_test_'));

        $todoId1 = new TodoId(uniqid('unit_test_'));
        $todoId2 = new TodoId(uniqid('unit_test_'));

        $teamMemberId = new TeamMemberId(uniqid('unit_test_'));
        $attachments = new Collection();

        $comment1 = new TodoComment($todoId1, $commentId1, $teamMemberId, 'Content 1', $attachments);
        $comment2 = new TodoComment($todoId2, $commentId2, $teamMemberId, 'Content 2', $attachments);
        $this->commentRepository->add($comment1);
        $this->commentRepository->add($comment2);
        $this->em->flush();

        $commentsOfTodo1 = $this->commentRepository->all($todoId1);
        $this->assertCount(1, $commentsOfTodo1);
        $this->assertEquals($commentId1, $commentsOfTodo1[0]->commentId());

        $commentsOfTodo2 = $this->commentRepository->all($todoId2);
        $this->assertCount(1, $commentsOfTodo2);
        $this->assertEquals($commentId2, $commentsOfTodo2[0]->commentId());

        $this->commentRepository->remove($comment1);
        $this->commentRepository->remove($comment2);
        $this->em->flush();
    }
}
