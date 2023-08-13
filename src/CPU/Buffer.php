<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CPU;

final class Buffer
{
    /** @var Job */
    private array $jobs = [];

    public function push(Job ...$jobs): void
    {
        array_push($this->jobs, ...$jobs);
    }

    public function pull(): ?Job
    {
        return array_shift($this->jobs);
    }

    public function isNotEmpty(): bool
    {
        return ! empty($this->jobs);
    }
}