<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CPU;

use Closure;

final class CPU
{
    private int $currentCycle = 1;

    /** @var list<Closure> */
    private array $watchers = [];

    public function __construct(
        private readonly Register $x = new Register(),
        private readonly Buffer $buffer = new Buffer(),
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
            }, __FUNCTION__, $register->value, $value),
        );

        $this->tick();
    }

    public function watch(Closure $using): void
    {
        $this->watchers[] = $using;
    }

    public function tick(): void
    {
        $job = $this->buffer->pull();

        if ($job) {
            foreach ($this->watchers as $watcher) {
                $watcher($job, $this->currentCycle, $this->x->value);
            }

            $job->run(on: $this);
        }

        $this->currentCycle++;
    }

    public function tickUntilBufferIsEmpty(): void
    {
        while ($this->buffer->isNotEmpty()) {
            $this->tick();
        }
    }
}