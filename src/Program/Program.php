<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\Program;

use EKvedaras\CathodeRayTube\CPU\CPU;

interface Program
{
    public function run(CPU $on): void;
}