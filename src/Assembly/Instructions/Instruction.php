<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\Assembly\Instructions;

use EKvedaras\CathodeRayTube\CPU;

interface Instruction
{
    public function run(CPU $cpu): void;
}