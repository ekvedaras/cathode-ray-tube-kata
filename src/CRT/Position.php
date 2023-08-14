<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CRT;

use Webmozart\Assert\Assert;

final readonly class Position
{
    public function __construct(public int $x, public int $y)
    {
        Assert::greaterThanEq($this->x, 0);
        Assert::greaterThanEq($this->y, 0);
    }

    public function asAsciiSequence(): AsciiPositionSequence
    {
        return new AsciiPositionSequence($this);
    }
}