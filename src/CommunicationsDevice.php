<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube;

use EKvedaras\CathodeRayTube\CPU\CPU;
use EKvedaras\CathodeRayTube\CRT\CRT;
use EKvedaras\CathodeRayTube\Program\Program;

final readonly class CommunicationsDevice
{
    public function __construct(
        private CPU $cpu,
        private CRT $display,
    ) {
        // Sync CPU and CRT clocks
        $this->cpu->prependWatcher(fn () => $this->display->tick());
    }

    public function run(Program $program): void
    {
        $program->run(on: $this->cpu);
    }
}