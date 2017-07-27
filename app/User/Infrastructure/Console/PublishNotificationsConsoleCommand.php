<?php
declare(strict_types=1);

namespace Teamo\User\Infrastructure\Console;

use Illuminate\Console\Command;
use Teamo\Common\Application\CommandBus;
use Teamo\User\Application\Command\Notification\PublishNotificationsCommand;

class PublishNotificationsConsoleCommand extends Command
{
    protected $signature = 'notifications:publish {exchange_name}';

    protected $description = 'Push unpublished notifications into Queue';

    protected $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();

        $this->commandBus = $commandBus;
    }

    public function handle()
    {
        $command = new PublishNotificationsCommand($this->argument('exchange_name'));
        $this->commandBus->handle($command);
    }
}
