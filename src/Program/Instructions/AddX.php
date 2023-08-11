<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\Program\Instructions;

use EKvedaras\CathodeRayTube\CPU\CPU;
use EKvedaras\CathodeRayTube\CPU\RegisterKey;

final readonly class AddX implements Instruction
{
    public function __construct(
        private int $value,
    ) {
    }

    public function run(CPU $cpu): void
    {
        $cpu->add(RegisterKey::x, $this->value);
    }
}