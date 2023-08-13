<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CPU;

use Closure;

final class Debugger
{
    /** @var array<int, Closure> */
    private array $breakpoints = [];

    /** @var list<Closure> */
    private array $evaluateOnEveryTick = [];

    public function attach(CPU $to): void
    {
        $to->watch(function (Job $pendingJob, int $currentCycle) use ($to) {
            foreach ($this->evaluateOnEveryTick as $expression) {
                $expression->call($to, $pendingJob);
            }

            if (isset($this->breakpoints[$currentCycle])) {
                $this->breakpoints[$currentCycle]->call($to, $pendingJob);
            }
        });
    }

    public function evaluate(Closure $expression, int $atCycle): void
    {
        $this->breakpoints[$atCycle] = $expression;
    }

    public function evaluateOnEveryTick(Closure $expression): void
    {
        $this->evaluateOnEveryTick[] = $expression;
    }
}