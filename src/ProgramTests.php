<?php

declare(strict_types=1);

use EKvedaras\CathodeRayTube\Assembly\Program;
use EKvedaras\CathodeRayTube\CPU;
use EKvedaras\CathodeRayTube\Register;
use EKvedaras\CathodeRayTube\RegisterKey;

it('runs program correctly', function (string $sourceCode, int $cyclesToRunFor, array $expectedXAtSpecificCycles) {
    $program = Program::load($sourceCode);
    $cpu = new CPU();

    foreach ($expectedXAtSpecificCycles as $cycleExpectation) {
        foreach ($cycleExpectation['registers'] as $registerKey => $registerExpectation) {
            if (isset($registerExpectation['start'])) {
                $cpu->runAtStart(ofCycle: $cycleExpectation['cycle'], command: function () use ($cycleExpectation, $registerKey, $registerExpectation) {
                    /** @var CPU $this */
                    expect($this->$registerKey)->toEqual(new Register((int) $registerExpectation['start']), "Register $registerKey value at the start of cycle {$cycleExpectation['cycle']} is not as expected");
                });
            }

            if (isset($registerExpectation['end'])) {
                $cpu->runAtEnd(ofCycle: $cycleExpectation['cycle'], command: function () use ($cycleExpectation, $registerKey, $registerExpectation) {
                    /** @var CPU $this */
                    expect($this->$registerKey)->toEqual(new Register((int) $registerExpectation['end']), "Register $registerKey value at the end of cycle {$cycleExpectation['cycle']} is not as expected");
                });
            }
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
        5,
        [
            ['cycle' => 1, 'registers' => [RegisterKey::x->value => ['start' => 1, 'end' => 1]]],
            ['cycle' => 2, 'registers' => [RegisterKey::x->value => ['start' => 1, 'end' => 1]]],
            ['cycle' => 3, 'registers' => [RegisterKey::x->value => ['start' => 1, 'end' => 4]]],
            ['cycle' => 4, 'registers' => [RegisterKey::x->value => ['start' => 4, 'end' => 4]]],
            ['cycle' => 5, 'registers' => [RegisterKey::x->value => ['start' => 4, 'end' => -1]]],
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
            ['cycle' => 20, 'registers' => [RegisterKey::x->value => ['start' => 21]]],
//            ['cycle' => 60, 'registers' => [RegisterKey::x->value => ['start' => 19]]],
//            ['cycle' => 100, 'registers' => [RegisterKey::x->value => ['start' => 18]]],
        ]
    ]
]);