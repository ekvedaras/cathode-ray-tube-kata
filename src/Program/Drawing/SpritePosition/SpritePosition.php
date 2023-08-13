<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\Program\Drawing\SpritePosition;

use Closure;
use EKvedaras\CathodeRayTube\CPU\CPU;
use EKvedaras\CathodeRayTube\CPU\Job;
use EKvedaras\CathodeRayTube\Program\InstructionSet;
use EKvedaras\CathodeRayTube\Program\Program;

final class SpritePosition implements Program
{
    private int $position = 0;

    public function __construct(
        private readonly Sprite $sprite,
        private readonly InstructionSet $instructionSet,
    ) {
    }

    public function run(CPU $on): void
    {
        $on->watch(function (Job $job, int $currentCycle, int $x) {
            $this->position = $x;
        });

        $this->instructionSet->run($on);
    }

    public function isSpriteVisibleAt(int $x): bool
    {
        return $x >= $this->position - ceil($this->sprite->size / 2)
            && $x <= $this->position + floor($this->sprite->size / 2);
    }
}