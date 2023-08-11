<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\Assembly\Instructions;

use EKvedaras\CathodeRayTube\CPU;
use EKvedaras\CathodeRayTube\RegisterKey;

final readonly class Add implements Instruction
{
    public function __construct(
        private RegisterKey $register,
        private int $value,
    ) {
    }

    public function run(CPU $cpu): void
    {
        $cpu->add($this->register, $this->value);
    }
}