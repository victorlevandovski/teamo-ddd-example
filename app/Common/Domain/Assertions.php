<?php
declare(strict_types=1);

namespace Teamo\Common\Domain;

trait Assertions
{
    protected function assertArgumentNotEmpty($value, string $message)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException($message);
        }
    }
}
