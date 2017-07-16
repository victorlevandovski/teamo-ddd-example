<?php

namespace Teamo\Common\Domain;

trait UpdatedOn
{
    protected $updatedOn;

    public function updatedOn(): \DateTimeImmutable
    {
        return $this->updatedOn;
    }

    protected function resetUpdatedOn()
    {
        $this->updatedOn = new \DateTimeImmutable();
    }

    protected function setUpdatedOn(\DateTimeImmutable $updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }
}
