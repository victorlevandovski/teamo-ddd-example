<?php
declare(strict_types=1);

namespace Teamo\Project\Infrastructure\Persistence\Doctrine;

use Illuminate\Support\Collection;
use Teamo\Common\Infrastructure\Persistence\DoctrineRepository;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionComment;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionCommentRepository;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;

class DoctrineDiscussionCommentRepository extends DoctrineRepository implements DiscussionCommentRepository
{
    public function add(DiscussionComment $comment)
    {
        $this->getEntityManager()->persist($comment);
    }

    public function remove(DiscussionComment $comment)
    {
        $this->getEntityManager()->remove($comment);
    }

    public function ofId(CommentId $commentId, DiscussionId $discussionId): DiscussionComment
    {
        $comment = $this->findOneBy(['commentId' => $commentId, 'discussionId' => $discussionId]);

        if (null === $comment) {
            throw new \InvalidArgumentException('Invalid comment id or discussion id');
        }

        return $comment;
    }

    public function all(DiscussionId $discussionId): Collection
    {
        return new Collection($this->findBy(['discussionId' => $discussionId]));
    }
}
