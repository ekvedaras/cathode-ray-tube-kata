<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CPU\Debugger;

use Closure;
use EKvedaras\CathodeRayTube\CPU\CPU;
use EKvedaras\CathodeRayTube\CPU\Job;

interface Debugger
{
    public function evaluate(Closure $expression, int $atCycle): void;

    public function evaluateOnEveryTick(Closure $expression): void;

    public function debug(CPU $cpu, int $currentCycle, Job $pendingJob): void;
}