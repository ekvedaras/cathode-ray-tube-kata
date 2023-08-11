<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CPU\Debugger;

use Closure;
use EKvedaras\CathodeRayTube\CPU\CPU;
use EKvedaras\CathodeRayTube\CPU\Job;

final class EnabledDebugger implements Debugger
{
    /** @var array<int, Closure> */
    private array $breakpoints = [];

    /** @var Closure */
    private array $evaluateOnEveryTick = [];

    public function evaluate(Closure $expression, int $atCycle): void
    {
        $this->breakpoints[$atCycle] = $expression;
    }

    public function evaluateOnEveryTick(Closure $expression): void
    {
        $this->evaluateOnEveryTick[] = $expression;
    }

    public function debug(CPU $cpu, int $currentCycle, Job $pendingJob): void
    {
        foreach ($this->evaluateOnEveryTick as $expression) {
            $expression->call($cpu, $pendingJob);
        }

        if (isset($this->breakpoints[$currentCycle])) {
            $this->breakpoints[$currentCycle]->call($cpu, $pendingJob);
        }
    }
}