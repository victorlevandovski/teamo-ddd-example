<?php

namespace Teamo\Common\Domain;

trait CreatedOn
{
    protected $createdOn;

    public function createdOn(): \DateTimeImmutable
    {
        return $this->createdOn;
    }

    protected function resetCreatedOn()
    {
        $this->createdOn = new \DateTimeImmutable();
    }

    protected function setCreatedOn(\DateTimeImmutable $createdOn)
    {
        $this->createdOn = $createdOn;
    }
}
