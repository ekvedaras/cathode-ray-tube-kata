<?php

use EKvedaras\CathodeRayTube\CPU;
use EKvedaras\CathodeRayTube\Register;
use EKvedaras\CathodeRayTube\RegisterKey;

test('noop does not change X register value even across multiple cycles', function () {
    $x = new Register(123);
    $cpu = new CPU(x: $x);

    $cpu->noop();
    expect($x)->toEqual(new Register(123));

    $cpu->noop();
    expect($x)->toEqual(new Register(123));

    $cpu->tick();
    expect($x)->toEqual(new Register(123));
});

test('add adds to X register and takes two cycles to complete', function () {
    $x = new Register();
    $cpu = new CPU(x: $x);

    $cpu->add(RegisterKey::x, 5);
    expect($x)->toEqual(new Register(1));

    $cpu->noop();
    expect($x)->toEqual(new Register(6));

    $cpu->noop();
    expect($x)->toEqual(new Register(6));
});