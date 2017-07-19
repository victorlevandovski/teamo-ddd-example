<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentId;
use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Tests\TestCase;

class DoctrineDiscussionRepositoryTest extends TestCase
{
    /** @var DiscussionRepository */
    private $discussionRepository;

    /** @var EntityManagerInterface */
    private $em;

    public function setUp()
    {
        parent::setUp();

        $this->em = app(EntityManagerInterface::class);
        $this->discussionRepository = $this->em->getRepository(Discussion::class);
    }

    public function testRepositoryCanAddAndRemoveDiscussion()
    {
        $projectId = new ProjectId(uniqid('unit_test_'));
        $discussionId = new DiscussionId(uniqid('unit_test_'));
        $teamMemberId = new TeamMemberId(uniqid('unit_test_'));
        $attachments = new Collection([new Attachment(new AttachmentId('a-1'), 'attachment.txt')]);

        $discussion = new Discussion($projectId, $discussionId, $teamMemberId, 'Topic', 'Content', $attachments);
        $this->discussionRepository->add($discussion);
        $this->em->flush();

        $savedDiscussion = $this->discussionRepository->ofId($discussionId, $projectId);
        $this->assertEquals('Topic', $savedDiscussion->topic());
        $this->assertEquals('Content', $savedDiscussion->content());
        $this->assertEquals($teamMemberId, $savedDiscussion->author());
        $this->assertEquals('attachment.txt', $discussion->attachments()['a-1']->name());
        $this->assertTrue($discussion->attachments()['a-1']->type()->isFile());

        $this->discussionRepository->remove($savedDiscussion);
        $this->em->flush();

        $this->expectException(\InvalidArgumentException::class);
        $this->discussionRepository->ofId($discussionId, $projectId);
    }

    public function testRepositoryReturnsOnlyActiveOrArchivedDiscussions()
    {
        $discussionId1 = new DiscussionId(uniqid('unit_test_'));
        $discussionId2 = new DiscussionId(uniqid('unit_test_'));

        $projectId = new ProjectId(uniqid('unit_test_'));
        $teamMemberId = new TeamMemberId(uniqid('unit_test_'));
        $attachments = new Collection();

        $discussion1 = new Discussion($projectId, $discussionId1, $teamMemberId, 'Topic', 'Content', $attachments);
        $discussion2 = new Discussion($projectId, $discussionId2, $teamMemberId, 'Topic', 'Content', $attachments);
        $discussion2->archive();

        $this->discussionRepository->add($discussion1);
        $this->discussionRepository->add($discussion2);
        $this->em->flush();

        $activeDiscussions = $this->discussionRepository->allActive($projectId);
        $this->assertCount(1, $activeDiscussions);
        $this->assertEquals($discussionId1, $activeDiscussions[0]->discussionId());

        $archivedDiscussions = $this->discussionRepository->allArchived($projectId);
        $this->assertCount(1, $archivedDiscussions);
        $this->assertEquals($discussionId2, $archivedDiscussions[0]->discussionId());

        $this->discussionRepository->remove($discussion1);
        $this->discussionRepository->remove($discussion2);
        $this->em->flush();
    }
}
