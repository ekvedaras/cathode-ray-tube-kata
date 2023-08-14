<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CRT;

use Stringable;

final readonly class AsciiPositionSequence implements Stringable
{
    public function __construct(private Position $position)
    {
    }

    public function column(): int
    {
        return $this->position->x + 1;
    }

    public function line(): int
    {
        return $this->position->y + 1;
    }

    public function __toString(): string
    {
        return "\e[{$this->line()};{$this->column()}H";
    }
}