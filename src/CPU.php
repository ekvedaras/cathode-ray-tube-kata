<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube;

use Closure;

final class CPU
{
    /** @var list<Closure> */
    private array $buffer = [];

    private int $currentCycle = 0;

    /** @var array<int, Closure> */
    private array $cycleStartCommands = [];

    /** @var array<int, Closure> */
    private array $cycleEndCommands = [];

    public function __construct(
        private readonly Register $x = new Register(),
    ) {
    }

    public function add(RegisterKey $register, int $value): void
    {
        $this->buffer[] = null;
        $this->buffer[] = function () use ($value, $register) {
            $this->{$register->value}->value += $value;
        };

        $this->tick();
    }

    private function startTick(): void
    {
        $this->currentCycle++;

        if (isset($this->cycleStartCommands[$this->currentCycle])) {
            $this->cycleStartCommands[$this->currentCycle]->call($this, $this->currentCycle);
        }
    }

    private function endTick(): void
    {
        if (isset($this->cycleEndCommands[$this->currentCycle])) {
            $this->cycleEndCommands[$this->currentCycle]->call($this, $this->currentCycle);
        }
    }

    public function tick(): void
    {
        $this->startTick();

        array_shift($this->buffer)?->call($this);

        $this->endTick();
    }

    public function tickUntil(int $cycle): void
    {
        while ($this->currentCycle <= $cycle) {
            $this->tick();
        }
    }

    public function runAtStart(int $ofCycle, Closure $command): void
    {
        $this->cycleStartCommands[$ofCycle] = $command;
    }

    public function runAtEnd(int $ofCycle, Closure $command): void
    {
        $this->cycleEndCommands[$ofCycle] = $command;
    }
}