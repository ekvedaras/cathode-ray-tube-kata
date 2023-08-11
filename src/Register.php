<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube;

final class Register
{
    public function __construct(
        private int $value = 1,
    ) {
    }

    public function add(int $value): void
    {
        $this->value += $value;
    }
}