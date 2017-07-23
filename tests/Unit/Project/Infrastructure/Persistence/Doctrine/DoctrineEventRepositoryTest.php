<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Teamo\Project\Domain\Model\Project\Event\Event;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\Event\EventRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Tests\TestCase;

class DoctrineEventRepositoryTest extends TestCase
{
    /** @var EventRepository */
    private $eventRepository;

    /** @var EntityManagerInterface */
    private $em;

    public function setUp()
    {
        parent::setUp();

        $this->em = app(EntityManagerInterface::class);
        $this->eventRepository = $this->em->getRepository(Event::class);
    }

    public function testRepositoryCanAddAndRemoveEvent()
    {
        $projectId = new ProjectId(uniqid('unit_test_'));
        $eventId = new EventId(uniqid('unit_test_'));
        $teamMemberId = new TeamMemberId(uniqid('unit_test_'));

        $event = new Event($projectId, $eventId, $teamMemberId, 'Name', 'Details', new \DateTimeImmutable('2020-01-01 12:00:00'));
        $this->eventRepository->add($event);
        $this->em->flush();

        $savedEvent = $this->eventRepository->ofId($eventId, $projectId);
        $this->assertEquals('Name', $savedEvent->name());
        $this->assertEquals('Details', $savedEvent->details());
        $this->assertEquals($teamMemberId, $savedEvent->creator());
        $this->assertEquals('2020-01-01', $savedEvent->occursOn()->format('Y-m-d'));

        $this->eventRepository->remove($savedEvent);
        $this->em->flush();

        $this->expectException(\InvalidArgumentException::class);
        $this->eventRepository->ofId($eventId, $projectId);
    }

    public function testRepositoryReturnsOnlyActiveOrArchivedEvents()
    {
        $eventId1 = new EventId(uniqid('unit_test_'));
        $eventId2 = new EventId(uniqid('unit_test_'));

        $projectId = new ProjectId(uniqid('unit_test_'));
        $teamMemberId = new TeamMemberId(uniqid('unit_test_'));

        $event1 = new Event($projectId, $eventId1, $teamMemberId, 'Name', 'Content', new \DateTimeImmutable());
        $event2 = new Event($projectId, $eventId2, $teamMemberId, 'Name', 'Content', new \DateTimeImmutable());
        $event2->archive();

        $this->eventRepository->add($event1);
        $this->eventRepository->add($event2);
        $this->em->flush();

        $activeEvents = $this->eventRepository->allActive($projectId);
        $this->assertCount(1, $activeEvents);
        $this->assertEquals($eventId1, $activeEvents[0]->eventId());

        $archivedEvents = $this->eventRepository->allArchived($projectId);
        $this->assertCount(1, $archivedEvents);
        $this->assertEquals($eventId2, $archivedEvents[0]->eventId());

        $this->eventRepository->remove($event1);
        $this->eventRepository->remove($event2);
        $this->em->flush();
    }
}
