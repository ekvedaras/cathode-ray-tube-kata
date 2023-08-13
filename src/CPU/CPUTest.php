<?php

use EKvedaras\CathodeRayTube\CPU\CPU;
use EKvedaras\CathodeRayTube\CPU\Register;
use EKvedaras\CathodeRayTube\CPU\RegisterKey;

test('simple tick does not change X register value even across multiple cycles', function () {
    $x = new Register(123);
    $cpu = new CPU(x: $x);

    $cpu->tick();
    expect($x)->toEqual(new Register(123));

    $cpu->tick();
    expect($x)->toEqual(new Register(123));

    $cpu->tick();
    expect($x)->toEqual(new Register(123));
});

test('add adds to X register and takes two cycles to complete', function () {
    $x = new Register();
    $cpu = new CPU(x: $x);

    $cpu->add(RegisterKey::x, 5);
    expect($x)->toEqual(new Register(1));

    $cpu->tick();
    expect($x)->toEqual(new Register(6));

    $cpu->tick();
    expect($x)->toEqual(new Register(6));
});

it('can tick until buffer is empty', function () {
    $cpuA = new CPU();
    $cpuA->add(RegisterKey::x, 0);
    $cpuA->add(RegisterKey::x, 0);
    $cpuA->add(RegisterKey::x, 0);
    $cpuA->tickUntilBufferIsEmpty();

    $cpuB = new CPU();
    $cpuB->tick();
    $cpuB->tick();
    $cpuB->tick();
    $cpuB->tick();
    $cpuB->tick();
    $cpuB->tick();

    expect($cpuA)->toEqual($cpuB);
});