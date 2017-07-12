<?php
declare(strict_types=1);

namespace Teamo\Common\Domain;

trait Assertions
{
    protected function assertArgumentNotEmpty($argument, string $message)
    {
        if (empty($argument)) {
            throw new \InvalidArgumentException($message);
        }
    }

    protected function assertArgumentMaxLength($argument, int $length, string $message)
    {
        if (mb_strlen($argument) > $length) {
            throw new \InvalidArgumentException($message);
        }
    }
}
