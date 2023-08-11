<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\Assembly\Instructions;

use EKvedaras\CathodeRayTube\CPU;

final readonly class Noop implements Instruction
{
    public function run(CPU $cpu): void
    {
        $cpu->tick();
    }
}