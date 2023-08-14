<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\Program\Drawing\SpritePosition;

use EKvedaras\CathodeRayTube\CPU\CPU;
use EKvedaras\CathodeRayTube\CPU\Job;
use EKvedaras\CathodeRayTube\Program\InstructionSet;
use EKvedaras\CathodeRayTube\Program\Program;

final class SpritePosition implements Program
{
    private int $position = 1;

    public function __construct(
        private readonly Sprite $sprite,
        private readonly InstructionSet $instructionSet,
    ) {
    }

    public function run(CPU $on): void
    {
        $on->prependWatcher(function (Job $job, int $currentCycle, int $x) {
            $this->position = max(1, $x);
        });

        $this->instructionSet->run($on);
    }

    public function isSpriteVisibleAt(int $x): bool
    {
        return $x >= $this->spriteStartsAt() && $x <= $this->spriteEndsAt();
    }

    private function spriteStartsAt(): int
    {
        return $this->position - (int) floor($this->sprite->size / 2);
    }

    private function spriteEndsAt(): int
    {
        return $this->position + (int) floor($this->sprite->size / 2);
    }
}