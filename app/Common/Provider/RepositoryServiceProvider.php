<?php

namespace Teamo\Common\Provider;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Teamo\User\Domain\Model\User\User;
use Teamo\User\Domain\Model\User\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    private function repositories()
    {
        return [
            UserRepository::class => User::class,
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
