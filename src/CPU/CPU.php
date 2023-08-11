<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CPU;

use EKvedaras\CathodeRayTube\CPU\Debugger\Debugger;
use EKvedaras\CathodeRayTube\CPU\Debugger\DisabledDebugger;

final class CPU
{
    private int $currentCycle = 1;

    public function __construct(
        private readonly Register $x = new Register(),
        private readonly Buffer $buffer = new Buffer(),
        private readonly Debugger $debugger = new DisabledDebugger(),
    ) {
    }

    public function noop(): void
    {
        $this->buffer->push(Job::sleep('noop'));

        $this->tick();
    }

    public function add(RegisterKey $register, int $value): void
    {
        $this->buffer->push(
            Job::sleep("add{$register->value}", $value),
            Job::make(function (string $register, int $value){
                $this->{$register}->value += $value;
            }, 'add', $register->value, $value),
        );

        $this->tick();
    }

    public function tick(): void
    {
        $job = $this->buffer->pull();

        if ($job) {
            $this->debugger->debug($this, $this->currentCycle, $job);

            $job->run(on: $this);
        }

        $this->currentCycle++;
    }

    public function tickUntil(int $cycle): void
    {
        while ($this->currentCycle <= $cycle) {
            $this->tick();
        }
    }
}