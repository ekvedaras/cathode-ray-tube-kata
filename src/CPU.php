<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube;

use Closure;

final class CPU
{
    /** @var list<Closure> */
    private array $buffer = [];

    public function __construct(
        private readonly Register $x = new Register(),
    ) {
    }

    public function noop(): void
    {
        $this->tick();
    }

    public function addx(int $value): void
    {
        // addx takes two cycles to complete
        $this->buffer[] = null;
        $this->buffer[] = fn () => $this->x->add($value);

        $this->tick();
    }

    private function tick(): void
    {
        $command = array_shift($this->buffer);
        $command && $command();
    }
}