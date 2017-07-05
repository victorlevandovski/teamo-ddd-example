<?php
declare(strict_types=1);

namespace Teamo\Common\Application;

interface Handler
{
    public function handle(Command $command);
}