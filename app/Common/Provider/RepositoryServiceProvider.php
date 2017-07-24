<?php

namespace Teamo\Common\Provider;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Teamo\Common\Domain\EventStore;
use Teamo\Common\Domain\StoredEvent;
use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionComment;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionCommentRepository;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionRepository;
use Teamo\Project\Domain\Model\Project\Event\Event;
use Teamo\Project\Domain\Model\Project\Event\EventComment;
use Teamo\Project\Domain\Model\Project\Event\EventCommentRepository;
use Teamo\Project\Domain\Model\Project\Event\EventRepository;
use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectRepository;
use Teamo\Project\Domain\Model\Team\TeamMember;
use Teamo\Project\Domain\Model\Team\TeamMemberRepository;
use Teamo\User\Domain\Model\User\User;
use Teamo\User\Domain\Model\User\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    private function repositories()
    {
        return [
            EventStore::class => StoredEvent::class,
            UserRepository::class => User::class,
            ProjectRepository::class => Project::class,
            TeamMemberRepository::class => TeamMember::class,
            DiscussionRepository::class => Discussion::class,
            DiscussionCommentRepository::class => DiscussionComment::class,
            EventRepository::class => Event::class,
            EventCommentRepository::class => EventComment::class,
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EntityManagerInterface::class, EntityManager::class);

        foreach ($this->repositories() as $repository => $entity) {
            $this->app->singleton($repository, function (Application $app) use ($entity) {
                return  $app->make(EntityManagerInterface::class)->getRepository($entity);
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
