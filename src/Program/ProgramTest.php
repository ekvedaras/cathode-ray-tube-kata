<?php

declare(strict_types=1);

use EKvedaras\CathodeRayTube\CPU\CPU;
use EKvedaras\CathodeRayTube\CPU\Debugger;
use EKvedaras\CathodeRayTube\CPU\Register;
use EKvedaras\CathodeRayTube\Program\InstructionSet;

it('runs program correctly', function (string $sourceCode, int $cyclesToRunFor, array $expectedXAtSpecificCycles) {
    $program = InstructionSet::load($sourceCode);
    $cpu = new CPU();
    $debugger = new Debugger();
    $debugger->attach(to: $cpu);

//    $debugger->evaluateOnEveryTick(function (Job $pendingJob) {
//        /** @var CPU $this */
//        dump("$this->currentCycle: x [{$this->x->value}] -> $pendingJob");
//    });

    foreach ($expectedXAtSpecificCycles as $cycleExpectation) {
        foreach ($cycleExpectation['registers'] as $registerKey => $expectedValue) {
            $debugger->evaluate(expression: function () use ($cycleExpectation, $registerKey, $expectedValue) {
                /** @var CPU $this */
                expect($this->$registerKey)->toEqual(new Register((int)$expectedValue), "Register $registerKey value at the end of cycle {$cycleExpectation['cycle']} is not as expected");
            }, atCycle: $cycleExpectation['cycle']);
        }
    }

    $program->run($cpu);
    $cpu->tickUntil($cyclesToRunFor);
})->with([
    'short explanatory example' => [
        <<<'Assembly'
            noop
            addx 3
            addx -5
        Assembly,
        6,
        [
            ['cycle' => 1, 'registers' => ['x' => 1]],
            ['cycle' => 2, 'registers' => ['x' => 1]],
            ['cycle' => 3, 'registers' => ['x' => 1]],
            ['cycle' => 4, 'registers' => ['x' => 4]],
            ['cycle' => 5, 'registers' => ['x' => 4]],
            ['cycle' => 6, 'registers' => ['x' => -1]],
        ],
    ],
    'long example' => [
        <<<'Assembly'
            addx 15
            addx -11
            addx 6
            addx -3
            addx 5
            addx -1
            addx -8
            addx 13
            addx 4
            noop
            addx -1
            addx 5
            addx -1
            addx 5
            addx -1
            addx 5
            addx -1
            addx 5
            addx -1
            addx -35
            addx 1
            addx 24
            addx -19
            addx 1
            addx 16
            addx -11
            noop
            noop
            addx 21
            addx -15
            noop
            noop
            addx -3
            addx 9
            addx 1
            addx -3
            addx 8
            addx 1
            addx 5
            noop
            noop
            noop
            noop
            noop
            addx -36
            noop
            addx 1
            addx 7
            noop
            noop
            noop
            addx 2
            addx 6
            noop
            noop
            noop
            noop
            noop
            addx 1
            noop
            noop
            addx 7
            addx 1
            noop
            addx -13
            addx 13
            addx 7
            noop
            addx 1
            addx -33
            noop
            noop
            noop
            addx 2
            noop
            noop
            noop
            addx 8
            noop
            addx -1
            addx 2
            addx 1
            noop
            addx 17
            addx -9
            addx 1
            addx 1
            addx -3
            addx 11
            noop
            noop
            addx 1
            noop
            addx 1
            noop
            noop
            addx -13
            addx -19
            addx 1
            addx 3
            addx 26
            addx -30
            addx 12
            addx -1
            addx 3
            addx 1
            noop
            noop
            noop
            addx -9
            addx 18
            addx 1
            addx 2
            noop
            noop
            addx 9
            noop
            noop
            noop
            addx -1
            addx 2
            addx -37
            addx 1
            addx 3
            noop
            addx 15
            addx -21
            addx 22
            addx -6
            addx 1
            noop
            addx 2
            addx 1
            noop
            addx -10
            noop
            noop
            addx 20
            addx 1
            addx 2
            addx 2
            addx -6
            addx -11
            noop
            noop
            noop
        Assembly,
        220,
        [
            ['cycle' => 20, 'registers' => ['x' => 21]],
            ['cycle' => 60, 'registers' => ['x' => 19]],
            ['cycle' => 100, 'registers' => ['x' => 18]],
            ['cycle' => 140, 'registers' => ['x' => 21]],
            ['cycle' => 180, 'registers' => ['x' => 16]],
            ['cycle' => 220, 'registers' => ['x' => 18]],
        ],
    ]
]);