<?php
declare(strict_types=1);

namespace Teamo\Common\Application;

interface CommandBus
{
    public function subscribe($command, $handler);

    public function handle($command);
}
