<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CPU;

final class Register
{
    public function __construct(
        public int $value = 1,
    ) {
    }
}