<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\Program\SignalStrength;

use Webmozart\Assert\Assert;

final readonly class Cycle
{
    private const contributesToSignalStrengthFromCycle = 20;
    private const contributesToSignalrStrengthEveryCycle = 40;

    private function __construct(private int $value)
    {
        Assert::positiveInteger($this->value);
    }

    public static function fromInt(int $value): self
    {
        return new self($value);
    }

    public function contributesToSignalStrength(): bool
    {
        return $this->isFirstRelevantCycle() || $this->isSubsequentRelevantCycle();
    }

    private function isFirstRelevantCycle(): bool
    {
        return $this->value === self::contributesToSignalStrengthFromCycle;
    }

    private function isSubsequentRelevantCycle(): bool
    {
        return (self::contributesToSignalStrengthFromCycle + $this->value) % self::contributesToSignalrStrengthEveryCycle === 0;
    }
}