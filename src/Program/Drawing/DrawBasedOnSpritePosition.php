<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\Program\Drawing;

use EKvedaras\CathodeRayTube\CPU\CPU;
use EKvedaras\CathodeRayTube\CPU\Job;
use EKvedaras\CathodeRayTube\CRT\CRT;
use EKvedaras\CathodeRayTube\Program\Drawing\SpritePosition\SpritePosition;
use EKvedaras\CathodeRayTube\Program\Program;

final readonly class DrawBasedOnSpritePosition implements Program
{
    public function __construct(
        private SpritePosition $spritePositionProgram,
        private CRT $crt,
    ) {
    }

    public function run(CPU $on): void
    {
        $on->watch(function (Job $job, int $currenCycle) {
            if ($this->spritePositionProgram->isSpriteVisibleAt(x: $currenCycle)) {
                $this->crt->activateForOneCycle();
            }
        });

        $this->spritePositionProgram->run($on);
    }
}