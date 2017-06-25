<?php

namespace Teamo\Common\Domain;

abstract class Entity
{
    protected function assertArgumentNotEmpty($value, $message)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException($message);
        }
    }
}
