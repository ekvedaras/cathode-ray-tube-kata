<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\Program\SignalStrength;

use EKvedaras\CathodeRayTube\CPU\CPU;
use EKvedaras\CathodeRayTube\CPU\Job;
use EKvedaras\CathodeRayTube\Program\InstructionSet;
use EKvedaras\CathodeRayTube\Program\Program;

final class SignalStrength implements Program
{
    private int $signalStrength = 0;

    public function __construct(private readonly InstructionSet $instructionSet)
    {
    }

    public function run(CPU $on): void
    {
        $on->watch(function (Job $job, int $currentCycle, int $x) {
            if (Cycle::fromInt($currentCycle)->contributesToSignalStrength()) {
                $this->signalStrength += $x * $currentCycle;
            }
        });

        $this->instructionSet->run($on);
    }

    public function output(): int
    {
        return $this->signalStrength;
    }
}