<?php
declare(strict_types=1);

namespace Teamo\User\Domain\Model\User;

use Teamo\Common\Domain\ValueObject;

class Avatar extends ValueObject
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function path()
    {
        return $this->path;
    }
}
