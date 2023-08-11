<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\Program\Instructions;

use EKvedaras\CathodeRayTube\CPU\CPU;

final readonly class Noop implements Instruction
{
    public function run(CPU $cpu): void
    {
        $cpu->noop();
    }
}