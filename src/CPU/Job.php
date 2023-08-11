<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CPU;

use BackedEnum;
use Closure;

final readonly class Job implements \Stringable
{
    /** @var list<mixed> */
    private array $arguments;

    private function __construct(
        private ?Closure $work,
        private string $instruction,
        int|string|BackedEnum ...$arguments,
    )
    {
        $this->arguments = $arguments;
    }

    public static function sleep(string $instruction, ...$arguments): self
    {
        return new self(null, $instruction, ...$arguments);
    }

    public static function make(Closure $work, string $instruction, ...$arguments): self
    {
        return new self($work, $instruction, ...$arguments);
    }

    public function run(CPU $on): void
    {
        $this->work?->call($on, ...$this->arguments);
    }

    public function __toString(): string
    {
        return implode(' ', [
            $this->instruction,
            ...$this->arguments,
            match (gettype($this->work)) {
                'NULL' => 'sleep',
                default => 'work',
            },
        ]);
    }
}