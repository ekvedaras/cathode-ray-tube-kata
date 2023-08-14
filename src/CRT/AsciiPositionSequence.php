<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CRT;

use Stringable;

final readonly class AsciiPositionSequence implements Stringable
{
    public function __construct(private Position $position)
    {
    }

    public function __toString(): string
    {
        return "\e[{$this->position->y};{$this->position->x}H";
    }
}