<?php
declare(strict_types=1);

namespace Teamo\Common\Application;

use Doctrine\ORM\EntityManagerInterface;

class SimpleCommandBus implements CommandBus
{
    private $commands;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function subscribe($command, $handler)
    {
        $this->commands[$command][] = $handler;
    }

    public function handle($command)
    {
        $commandClass = get_class($command);

        if (isset($this->commands[$commandClass])) {
            foreach ($this->commands[$commandClass] as $handlerClass) {
                $handler = app($handlerClass);
                $handler->handle($command);
                $this->em->flush();
            }
        } else {
            throw new \InvalidArgumentException("Command {$commandClass} does not have any handler mapped");
        }
    }
}
