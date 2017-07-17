<?php
declare(strict_types=1);

namespace Teamo\Common\Infrastructure\Persistence;

use Doctrine\ORM\EntityRepository;
use Illuminate\Support\Collection;

abstract class DoctrineRepository extends EntityRepository
{
    protected $castToArray = [];

    protected function processEntity($entity)
    {
        if ($this->castToArray) {
            $this->castPropertiesToArray($entity, $this->castToArray);
        }

        return $entity;
    }

    protected function processEntities(array $entities): Collection
    {
        if ($this->castToArray) {
            foreach ($entities as $entity) {
                $this->castPropertiesToArray($entity, $this->castToArray);
            }
        }

        return new Collection($entities);
    }

    protected function castPropertiesToArray($entity, array $properties): void
    {
        $casting = function() use ($properties) {
            foreach ($properties as $property) {
                if (!is_array($this->$property)) {
                    $this->$property = $this->$property->toArray();
                }
            }
        };

        $cast = $casting->bindTo($entity, $entity);
        $cast();
    }
}
