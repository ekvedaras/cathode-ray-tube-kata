<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CPU;

final readonly class Throttle
{
    public function __construct(private int $microseconds)
    {
    }

    public function attach(CPU $to): void
    {
        $to->watch(function () { usleep($this->microseconds); });
    }
}