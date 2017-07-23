<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\Doctrine;

use Illuminate\Support\Collection;
use Teamo\Common\Infrastructure\Persistence\DoctrineRepository;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Event\EventComment;
use Teamo\Project\Domain\Model\Project\Event\EventCommentRepository;
use Teamo\Project\Domain\Model\Project\Event\EventId;

class DoctrineEventCommentRepository extends DoctrineRepository implements EventCommentRepository
{
    public function add(EventComment $comment)
    {
        $this->getEntityManager()->persist($comment);
    }

    public function remove(EventComment $comment)
    {
        $this->getEntityManager()->remove($comment);
    }

    public function ofId(CommentId $commentId, EventId $eventId): EventComment
    {
        $comment = $this->findOneBy(['commentId' => $commentId, 'eventId' => $eventId]);

        if (null === $comment) {
            throw new \InvalidArgumentException('Invalid comment id or event id');
        }

        return $comment;
    }

    public function all(EventId $eventId): Collection
    {
        return new Collection($this->findBy(['eventId' => $eventId]));
    }
}
